<?php
namespace App\Http\Controllers;
use App\Events\OurExampleEvent;
use App\Models\Follow;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use Intervention\Image\Laravel\Facades\Image;


class Usercontroller extends Controller
{
    public function homePageView()
    {
        if(auth()->check()){
            return view('home-feed',[
                'postdata' => auth()->user()->postsOfFollowedUser()->latest()->paginate(4),
            ]);
        }else{
//            if(Cache::has('postCount')){
//                $postCount = Cache::get('postCount');
//            }else{
//                $postCount=Post::count();
//                Cache::put('postCount',$postCount,10);
//            }

            $postCount = Cache::remember('postCount', 60, function () {
                return Post::count();
            });
            return view('home',['postCount' => $postCount]);
        }
    }
    public function userSubmittedData(Request $request){
    $userGeneratedData = $request->validate([
        'username' => ['required','string','min:3','max:15', Rule::unique('users','username')],
        'email' => ['required','email',Rule::unique('users','email')],
        'password' => ['required','min:8','confirmed']

    ]);
        $userGeneratedData['password'] = bcrypt($userGeneratedData['password']);
    $userdata = User::create($userGeneratedData);
    auth()->login($userdata);
    return redirect('/')->with('Success','You are logged in');
    }
    public function userLoggedIn(Request $request)
    {
        $loginData = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);
       if(auth()->attempt(['username' => $loginData['loginusername'], 'password' => $loginData['loginpassword']])){
           $request->session()->regenerate();
           event(new OurExampleEvent(['username'=>auth()->user()->username, 'action'=>'Log IN']));
           return redirect('/')->with('success', 'Welcome Back');
       }else{
            return redirect('/')->with('message', 'Login Details Are Wrong');
       }
    }
    public function userLogOut(request $request)
    {
        $userName = Auth::user()->username;
        $request->session()->put('user_name', $userName);
        event(new OurExampleEvent(['username'=>auth()->user()->username, 'action'=>'Log out']));
        auth()->logout();
        return redirect('/')->with('success','You have been logged out');
    }


    public function uploadAvatarView(){
        return view('upload-avatar');
    }

public function uploadAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user = Auth::user();
        $filename = $user->id.'-'.uniqid().'.jpg';
        $oldAvatar = $user->avatar;
        $imageData = Image::read($request->file('avatar'))->cover(120,120)->toJpeg();
        Storage::put('/public/avatars/'.$filename, $imageData);
        $user->avatar = $filename;
        $user->save();
        if($oldAvatar != '/default.jpg'){
            Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
        }
        return redirect('/profile/'.auth()->user()->username)->with('success', 'Your avatar has been Changed');
}
private function getDataforShare($user){
    $userFollowed = 0;
    if(auth()->check()){
        $userFollowed = Follow::where('user_id', auth()->user()->id)->where('followed_id',$user->id)->count();
    }
    View::share('getData', [
        'userFollowed'=>$userFollowed,
        'username' => $user->username,
        'avatar'=> $user->avatar,
        'postcount'=>$user->posts()->count(),
        'followcount'=>$user->followers()->count(),
        'followingcount'=>$user->following()->count(),
        ]);


}
    public function profileView(User $user)
    {
        $this->getDataforShare($user);
        return view('single-profile', [
            'postsdata' => $user->posts()->latest()->get()
        ]);
    }
    public function profileViewRaw(User $user)
    {
        return response()->json(['theHTML' => view('profile-posts-only', ['postsdata' => $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Profile"]);
        //return response()->json(['theHTML' => view('profile-posts-only', ['postsdata' => $user->posts()->latest()->get()])->render(), 'docTitle'=> $user->username."'s profile"]);
    }
    public function profileFollowersView(User $user)
    {
        $this->getDataforShare($user);
        //return $user->followers()->latest()->get();

        return view('single-profile-followers', [
            'followers' => $user->followers()->latest()->get(),
            ]);
    }
    public function profileFollowersViewRaw(User $user)
    {
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
        //return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
    }
    public function profileFollowingView(User $user)
    {
        $this->getDataforShare($user);
        return view('single-profile-following', [
            'following' => $user->following()->latest()->get()]);
    }

    public function profileFollowingViewRaw(User $user)
    {
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->following()->latest()->get()])->render(), 'docTitle' => 'Who ' . $user->username . " Follows"]);
        //return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->following()->latest()->get()])->render(), 'docTitle' => "Followed by". $user->username]);

    }


}
