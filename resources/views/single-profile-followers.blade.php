<x-profile :getData="$getData" doctitle="{{$getData['username']}}'s Followers">
    @include('profile-followers-only')
</x-profile>
