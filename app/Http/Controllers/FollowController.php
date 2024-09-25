<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function getFollow(User $user){
        if(auth()->user()->id == $user->id){
            return back()->with('message','You can\'t follow yourself');
        }
        if(Follow::where('user_id',auth()->user()->id)->where('followed_id',$user->id)->exists()){
            return back()->with('message','you Already followed');
        }
    $follow = new Follow();
    $follow->user_id = auth()->user()->id;
    $follow->followed_id= $user->id;
    $follow->save();
    return redirect('/profile/'.$user->username)->with('message','You Have Followed'.$user->username);
    }
    public function removeFollow(User $user){
        Follow::where('user_id',auth()->user()->id)->where('followed_id',$user->id)->delete();
        return back()->with('message','You unfollowed '.$user->username);
    }
}
