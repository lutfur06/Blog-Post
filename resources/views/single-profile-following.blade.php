<x-profile :getData="$getData" doctitle="Followed by {{$getData['username']}}">
    @include('profile-following-only')
</x-profile>
