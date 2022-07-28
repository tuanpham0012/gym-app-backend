<?php

namespace App\Http\Controllers\Api;

use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Events\UpdateCommentPosted;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Post;

class PostCommentController extends Controller
{
    protected $comment;
    public function __construct(PostComment $comment)
    {
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::with('user:id,name')->find($request->post_id);
        $comment = new PostComment();
        $comment->fill($request->all());
        $comment->user_id = $request->user()->id;
        $comment->save();
        $content = '<h6>'.$post->user->name. '</h6> đã bình luận về bài viết của bạn';
        Helpers::createNotification($post->user_id, $request->user()->id, $post->id, 'posts', $content);
        broadcast(new UpdateCommentPosted($request->post_id))->toOthers();
        return response()->json([
            'message' => 'Comment success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comments = $this->comment->with('user:id,name,avatar')->where('post_id', $id)->latest()->get();
        return response()->json($comments, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
