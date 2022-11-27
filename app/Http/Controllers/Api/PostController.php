<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')
            ->filter()
            ->search()
            ->order()
            ->paginate(20);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        if (\request()->hasFile('attachment'))
        {
            $filename = $request->file('attachment')->getClientOriginalName();
            info($filename);
        }

        $post = Post::query()->create($request->validated());

        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(Post $post, StorePostRequest $request)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    public function destroy(Post $post) {
        $post->delete();

        return response()->noContent();
    }
}
