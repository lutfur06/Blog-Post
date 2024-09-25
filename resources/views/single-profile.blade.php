<x-profile :getData="$getData" doctitle="{{$getData['username']}}'s Profile">
    @include('profile-posts-only')
</x-profile>
