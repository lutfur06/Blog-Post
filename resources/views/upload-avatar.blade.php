<x-layout doctitle="Upload Avatar">
    <div class="container container--narrow py-5">
        <form action="/upload-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleFormControlFile1">Avatar file input</label>
                <input type="file" name="avatar" class="form-control-file" id="exampleFormControlFile1">
            <button class="btn btn-info btn-sm my-2" type="submit">Submit</button>
            </div>
        </form>
    </div>

</x-layout>
