<?php

namespace App\Http\Controllers\Api;

use App\Models\PostReact;
use Illuminate\Http\Request;
use App\Jobs\SendPasswordMail;
use App\Events\NotificationPosted;
use App\Http\Controllers\Controller;

class PostReactController extends Controller
{
    protected $react;
    public function __construct(PostReact $react)
    {
        $this->react = $react;
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
        $react = $this->react::where('post_id', $request->post_id)->where('user_id', $request->user()->id)->first();
        // broadcast(new NotificationPosted('abc xad'))->toOthers();

        if($react){
            $react->delete();
            $reacts = $this->react->where('post_id', $request->post_id)->get()->count();
            return response()->json([
                'message' => 'unlike success',
                'react_count' => $reacts,
                'liked' => false,
            ], 200);
        }else{
            $react = new PostReact();
            $react->post_id = $request->post_id;
            $react->user_id = $request->user()->id;
            $react->save();
            $reacts = $this->react->where('post_id', $request->post_id)->get()->count();
            return response()->json([
                'message' => 'Success',
                'react_count' => $reacts,
                'liked' => true,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $react = $this->react::where('post_id', $request->post_id)->where('user_id', $request->user()->id)->first();
        if($react){
            $react->delete();
            $reacts = $this->react->where('post_id', $request->post_id)->get()->count();
            return response()->json([
                'message' => 'unlike success',
                'react_count' => $reacts,
            ], 200);
        }else{
            $react = new PostReact();
            $react->post_id = $request->post_id;
            $react->user_id = $request->user()->id;
            $react->save();
            $reacts = $this->react->where('post_id', $request->post_id)->get()->count();
            return response()->json([
                'message' => 'Success',
                'react_count' => $reacts,
            ], 200);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $react = PostReact::find($id)->delete();
        return response()->json([
            'message' => 'unlike success'
        ], 200);

    }
}
