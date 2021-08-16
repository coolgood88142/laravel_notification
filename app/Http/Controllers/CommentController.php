<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Events\AddComment;
use App\Events\RedisMessage;
use App\Events\SendMessage;
use App\Models\Articles;
use App\Models\Comment;
use App\User;
use Carbon\Carbon;
use Pusher\Pusher;
use Auth;

class CommentController extends Controller
{
    public function editComment(Request $request)
    {
        $articleId = $request->articleId;
        $userId = $request->userId;

        $articles = Articles::where('id', '=', $articleId)->first();
        $authorId = $articles->author_id;
        $title = $articles->title;
        $userName = User::where('id', '=', $userId)->first()->name;
        $authorName = User::where('id', '=', $authorId)->first()->name;
        // $comment = DB::select('select * from comment where articles_id = ? and user_id = ?', [$articleId, $userId]);
        // $text = $comment[0]->text;

        return view('comment', [
            'articleId' => $articleId,
            'authorName' => $authorName,
            'userId' => $userId,
            'userName' => $userName,
            'title' => $title
        ]);
    }

    public function addComment(Request $request)
    {
        $userId = $request->userId;
        $articleId = $request->articleId;
        $text = $request->InputComment;
        $title = $request->title . '有一則新留言';

        $status = 'success';
        try{
            //先發通知在新增
            //先組作者、留言
            $idArray = [];
            $articles = Articles::where('id', '=', $articleId)->first();
            $authorId = $articles->author_id;
            
            if($authorId != $userId){
                array_push($idArray, $authorId);
            }

            $comments = Comment::where('articles_id', '=', $articleId)->get();
            foreach($comments as $comment){
                if($comment->user_id != $userId){
                    array_push($idArray, $comment->user_id);
                }
            }

            $users = User::WhereIn('id', $idArray)->get();
            event(new AddComment($users,  $title, $articleId));

            $pusher = new Pusher(
                '408cd422417d5833d90d',
                '2cb040ab9efbb676ed8b',
                '1243356', 
                array(
                    'cluster' => 'ap3',
                    'encrypted' => true
                )
            );

            // $notification = $user->notifications()->where('data->status', '=', 'addComment')->first();
            set_time_limit(0);
            $data['message'] = '您有一篇新訊息【' . $title. '】';
            $data['userData'] = [
                'userId' => Auth::id(),
                'articleId' => $articleId,
                // 'notificationId' => $notification->id,
                'isRead' => 'N',
                'status' => 'addComment'
            ];

            $data['users'] = $users;

            //怎麼從10000個送到前端時，去只到對應的channel

            event(new SendMessage($data));
            // $pusher->trigger('article-channel' . Auth::id(), 'App\\Events\\SendMessage', $data);
            // event(new RedisMessage($data));

            // foreach($users as $user){
                
    
            // }

            $comment = new Comment();
            $comment->user_id = $userId;
            $comment->articles_id = $articleId;
            $comment->text = $text;
            $comment->save();
            
        }catch(Exception $e){
            $status = 'error';
        }

        return redirect()->route('showArticleContent',  [
            'id' => $articleId,
            'userId' => $userId,
            'isAdd' => 'Y'
        ]);
    }
}
