<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;
use App\Models\Channels;
use App\Models\ChannelsArticles;

class ChannelsController extends Controller
{
    
    public function showAddChannels(Request $request)
    {
        $userId = $request->userId;
        $articles = Articles::orderBy('id')->get();

        return view('addChannel', [
            'userId' => $userId,
            'articles' => $articles
        ]);
    }

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

        $name = '新頻道 ' .$InputChannelsName;

        foreach (\App\User::cursor() as $user) {
            event(new AddChannels($users,  $name, $channelsId));

            $notification = $user->notifications()->where('data->status', '=', 'addChannel')->first();
                
            $data['message'] = '您有一篇新訊息【' . $name. '】';
            $data['userData'] =  [
                'userId' => $user->id,
                'channelsId' => $channelsId,
                'notificationId' => $notification->id,
                'isRead' => 'N',
                'status' => 'addChannel'
            ];

            $pusher->trigger('article-channel', 'App\\Events\\SendMessage', $data);
            event(new RedisMessage($data));
        }

        return redirect()->route('showAritcles');
    }

    public function showChannelsContent(Request $request)
    {
        $userId = $request->userId;
        $channelsId = $request->channelsId;
        $channels = Channels::where('id', '=', $channelsId)->first();
        $idArray = [];

        $channelsArticles = ChannelsArticles::where('channels_id', '=', $channels->id)->get();
        
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
