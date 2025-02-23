<x-layout doctitle="Create Post">
    <div class="container py-md-5 container--narrow">
        <form action="/create-post" method="POST">
            @csrf
            <div class="form-group">
                @error('title')
                <p class="alert alert-danger">{{$message}}</p>
                @enderror
                <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
                <input value="{{old('title')}}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
            </div>

            <div class="form-group">
                @error('body')
                <p class="alert alert-danger">{{$message}}</p>
                @enderror
                <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
                <textarea  name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{old('body')}}</textarea>
            </div>

            <button class="btn btn-primary">Save New Post</button>
        </form>
    </div>
</x-layout>
