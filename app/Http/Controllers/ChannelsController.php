<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\Channels;
use App\Models\ChannelsArticles;
use App\Events\AddChannels;
use App\Events\RedisMessage;
use Pusher\Pusher;

class ChannelsController extends Controller
{
    public $name;
    public $channelsId;

    //顯示新增頻道畫面
    public function showAddChannels(Request $request)
    {
        $userId = $request->userId;
        $articles = Articles::orderBy('id')->get();

        return view('addChannel', [
            'userId' => $userId,
            'articles' => $articles
        ]);
    }

    //新增頻道
    public function addChannels(Request $request)
    {
        $userId = $request->userId;
        $InputChannelsName = $request->InputChannelsName;
        $articles = $request->articlesValue;
        $articlesData = [];

        if($articles != '' || $articles != null){
            $articlesData = explode(",", $articles);
        }

        $channels = new Channels();
        $channels->name = $InputChannelsName;
        $channels->save();
        $channelsId = $channels->id;

        foreach($articlesData as $key => $article){
            $channelsArticles = new ChannelsArticles();
            $channelsArticles->channels_id = $channelsId;
            $channelsArticles->articles_id = $article;
            $channelsArticles->save();
        }

        $name = '您有一篇新訊息【新頻道：' . $InputChannelsName . '】';

        $this->name = $name;
        $this->channelsId = $channelsId;

        $pusher = new Pusher(
            '408cd422417d5833d90d',
            '2cb040ab9efbb676ed8b',
            '1243356', 
            array(
                'cluster' => 'ap3',
                'encrypted' => true
            )
        );

        set_time_limit(0);
        \App\User::chunk(10000, function($users)
        {   
            event(new AddChannels($users,  $this->name, $this->channelsId));

            $notification = $user->notifications()->where('data->type', '=', 'addChannel')->first();
            $data['message'] =  $name;
            $data['userData'] =  [
                'userId' => $user->id,
                'id' => $channelsId,
                'notificationId' => $notification->id,
                'isRead' => 'N',
                'type' => 'addChannel'
            ];
            $pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
            event(new RedisMessage($data));
        });

        return redirect()->route('showAritcles');
    }

    //顯示單個頻道中，所有的文章資料
    public function showChannelContent(Request $request)
    {
        $userId = $request->userId;
        $notificationId = $request->notificationId;
        $isRead = $request->isRead;
        $channelsId = $request->channelsId;
        $channels = Channels::where('id', '=', $channelsId)->first();
        $idArray = [];

        $channelsArticles = ChannelsArticles::where('channels_id', '=', $channels->id)->get();

        if($notificationId != null && $userId != null && $isRead == 'N'){
            //點選後直接做已閱讀
            $controller = new ArticlesController();
            $controller->readNotifications($notificationId, $userId);
        }
        
        foreach($channelsArticles as $channel){
            array_push($idArray, $channel->articles_id);
        }

        $articles = Articles::WhereIn('id', $idArray)->get();
        
        return view('channelsArticles', [
            'userId' => $userId,
            'articles' => $articles
        ]);
    }
}
