<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostReact;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->post->with('user:id,name,avatar', 'images:id,url,post_id')->withCount(['comments', 'reacts'])->where('post_status_id', 2)->latest()->paginate(10);
        foreach($posts as $post){
            $react = PostReact::where('post_id', $post->id)->where('user_id', request()->user()->id)->first();
            if($react){
                $post->liked = true;
            }else{
                $post->liked = false;
            }
        }
        return response()->json([
            'posts' => $posts
        ], 200);
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
        $post = new Post();
        $post->content = $request->content;
        $post->user_id = $request->user()->id;
        $post->post_status_id = 1;
        $post->admin_id = 1;
        $post->save();
        $count = 0;
        $files = $request->file('images');
        if($request->hasFile('images')){
            $count = 1;
            foreach($files as $file){
                $fileNameWithExt = $file->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStoge = time().'_'.$fileName. '.' .$extension;

                $file->storeAs('public/images', $fileNameToStoge);
                $image = new PostImage();
                $image->url = $fileNameToStoge;
                $image->post_id = $post->id;
                $image->save();
                $count++;
            }
            
        }
        return response()->json([
            'message' => 'Đăng bài thành công!',
            'post' => $count,
            'file' => $request->file('images'),
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
        $post = $this->post->with('user:id,name,avatar', 'images:id,url,post_id')->withCount(['reacts','comments'])->where('post_status_id', 2)->find($id);
        $react = PostReact::where('post_id', $post->id)->where('user_id', request()->user()->id)->first();
            if($react){
                $post->liked = true;
            }else{
                $post->liked = false;
            }
        return response()->json($post, 200);
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
