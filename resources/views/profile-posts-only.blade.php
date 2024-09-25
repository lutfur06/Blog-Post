<div class="list-group">
    @foreach($postsdata as $post)
        <x-posts :post="$post" hideAuthor/>
    @endforeach
</div>

