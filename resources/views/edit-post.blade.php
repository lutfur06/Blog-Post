<x-layout doctitle="Edit Post">
    <div class="container py-md-5 container--narrow">
        <a href="/posts/{{$post->id}}">&laquo; Back to post permalink</a>
        <form action="/posts/{{$post->id}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                @error('title')
                <p class="alert alert-danger">{{$message}}</p>
                @enderror
                <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
                <input value="{{old('title', $post->title)}}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
            </div>

            <div class="form-group">
                @error('body')
                <p class="alert alert-danger">{{$message}}</p>
                @enderror
                <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
                <textarea  name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{old('body', $post->body)}}</textarea>
            </div>

            <button class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</x-layout>
