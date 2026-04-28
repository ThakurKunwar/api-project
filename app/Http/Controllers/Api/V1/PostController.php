<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = request()->user();
        $posts = $user->posts()->with('user')->paginate();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);
        return response()->json(
            [new PostResource($post->load('user'))],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = Post::findOrFail($id);
        $user = request()->user();

        abort_if(Auth::id() != $post->user_id, 403, 'access forbidden');


        return new PostResource($post->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $post = Post::findOrFail($id);
        abort_if(Auth::id() != $post->user_id, 403, 'access forbidden');

        $data =  $request->validate([
            'title' => ['required', 'min:4'],
            'body' => ['required', 'string', 'min:10'],
        ]);


        $post->update($data);
        return (new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $post = Post::findOrFail($id);

        abort_if(Auth::id() != $post->user_id, 403, 'access forbidden');

        $post->delete();

        return response()->noContent();
    }
}
