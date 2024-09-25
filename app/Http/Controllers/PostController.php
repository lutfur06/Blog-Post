<?php

namespace App\Http\Controllers;



use App\Jobs\SendNewPostMail;
use Illuminate\Http\Request;
use App\Models\Post;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function createPostView()
    {
        return view('create-post');
    }

    public function createPost(Request $request)
    {
        $getResultsFromPost = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $getResultsFromPost['title'] = strip_tags($getResultsFromPost['title']);
        $getResultsFromPost['body'] = strip_tags($getResultsFromPost['body']);
        $getResultsFromPost['user_id'] = auth()->user()->id;
        $createdPost = Post::create($getResultsFromPost);
        dispatch(new SendNewPostMail(['send'=>auth()->user()->email,'name'=>auth()->user()->username, 'title'=>$createdPost->title]));

        return redirect("/posts/{$createdPost->id}")->with('success', 'Post has been created');
    }

    public function showPost(Post $post)
    {
//        echo '<pre>';
//        var_dump($post);
//        echo '</pre>';
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><br><h2><h3><h4><h5><ul><li><em><strong><mark>');
        return view('show-post', ['post' => $post]);

    }

    public function deletePost(Post $post)
    {
        if (auth()->user()->cannot('delete', $post)) {
            return redirect('/posts/{$post->id}')->with('message', 'You can not delete this post');
        }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post has been deleted');
    }

    public function editPost(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }

    public function updatePost(Request $request, Post $post)
    {
        $getResultsFromPost = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $getResultsFromPost['title'] = strip_tags($getResultsFromPost['title']);
        $getResultsFromPost['body'] = strip_tags($getResultsFromPost['body']);
        $post->update($getResultsFromPost);
        return redirect('/posts/'.$post->id)->with('success', 'Post has been updated');
    }
    public function searchPost($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
        //return Post::where('title', 'LIKE', '%' . $term . '%')->orWhere('body', 'LIKE', '%' . $term . '%')->with('user:id,username,avatar')->get();
    }
}
