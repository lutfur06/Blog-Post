<div class="list-group">
    @foreach($followers as $follow)

        <a href="/profile/{{$follow->userDoFollowing->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follow->userDoFollowing->avatar}}" />
            <strong>{{$follow->userDoFollowing->username}}</strong>
        </a>
    @endforeach
</div>
