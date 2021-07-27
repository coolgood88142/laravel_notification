<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\AddArticles;
use App\Models\Articles;
use App\Models\Comment;
use App\User;

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
        $title = $request->title;

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
                array_push($idArray, $comment->user_id);
            }
            
            
            $user = User::WhereIn('id', $idArray)->get();
            event(new AddArticles($user,  $title . '有一則新留言', $articleId, 'comment'));

            $comment = new Comment();
            $comment->user_id = $userId;
            $comment->articles_id = $articleId;
            $comment->text = $text;
            $comment->save();

            
        }catch(Exception $e){
            $status = 'error';
        }

        return redirect()->route('editArticles',  [
            'id' => $articleId,
            'userId' => $userId,
            'isAdd' => 'Y'
        ]);
    }
}
