<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostStatus;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{

    protected $post;
    protected $status;
    public function __construct(Post $post, PostStatus $status)
    {
        $this->post = $post;
        $this->status = $status;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->post->with(['user:id,name,avatar', 'status'])->withCount('images')
        ->where(function($query) use($request){
            if(isset($request->post_status_id) && $request->post_status_id != -1 ){
                $query->where('post_status_id', $request->post_status_id);
                }
        })
        ->orderBy('post_status_id')->latest()->paginate(15);
        $status = $this->status->get();
        return response()->json([
            'posts' => $posts,
            'status' => $status,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->post->with(['user:id,name,avatar', 'images', 'status'])->find($id);
        return response()->json([
            'post' => $post,
        ], 200);
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
        $request->admin_id = $request->user()->id;
        $post = $this->post->find($id)->update($request->all());
        return response()->json([
            'message' => 'C???p nh???t th??nh c??ng!',
        ], 200);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_image($id)
    {
        $image = PostImage::query()->find($id);
        if($image){
            try {
                unlink(public_path('storage/images/'.$image->url));
                $image->delete();
                return response()->json([
                    'message' => 'X??a h??nh ???nh th??nh c??ng!',
                ], 200);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'message' => 'C?? l???i x???y ra',
                    'error' => $th
                ], 500);
            }
        }
        return response()->json([
            'message' => 'Kh??ng t??n th???y h??nh ???nh!!',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept_all()
    {
        $posts = $this->post->where('post_status_id', 1)->get();
        $admin_id = request()->user()->id;
        foreach($posts as $post){
            $post->post_status_id = 2;
            $post->admin_id = $admin_id;
            $post->save();
            Helpers::createNotification($post->user_id, 1, $post->id, 'posts', 'B??i vi???t c???a b???n ???? ???????c duy???t!');
        }
        return response()->json([
            'message' => 'C???p nh???t th??nh c??ng!!',
        ], 200);
    }
}
