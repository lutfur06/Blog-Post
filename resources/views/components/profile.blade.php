<x-layout :doctitle="$doctitle">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{$getData['avatar']}}" /> {{$getData['username']}}
            @auth



                @if($getData['userFollowed'])
                    <form class="ml-2 d-inline" action="/remove/{{$getData['username']}}" method="POST">
                        @csrf

                        <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>

                    </form>
                @else
                    @if(auth()->user()->username !== $getData['username'])
                        <form class="ml-2 d-inline" action="/follow/{{$getData['username']}}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                        </form>
                    @endif
                @endif

                @if(auth()->user()->username === $getData['username'])
                    <a href="/upload-avatar" class="btn btn-secondary btn-sm">Upload Avatar </a>
                @endif

            @endauth


        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{$getData['username']}}" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "" ? "active" : "" }}">Posts: {{$getData['postcount']}}</a>
            <a href="/profile/{{$getData['username']}}/followers" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "followers" ? "active" : "" }}">Followers:
                {{$getData['followcount']}}</a>
            <a href="/profile/{{$getData['username']}}/following" class="profile-nav-link nav-item nav-link {{Request::segment(3) == "following" ? "active" : "" }}">Following: {{$getData['followingcount']}}</a>
        </div>
    <div class="class-for-show-profile">
        {{$slot}}
    </div>


    </div>
</x-layout>
