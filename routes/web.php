<?php

use App\Events\ChatMessage;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\Newcontroller;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/admin-only', function (){
        return "this is admin only";
})->middleware('can:visitAdminPage');
Route::get('/', [Usercontroller::class, 'homePageView'])->name('login');
Route::get('/single-post',[Newcontroller::class,'singlePostView']);
Route::post('/submit',[Usercontroller::class,'userSubmittedData'])->middleware('guest');
Route::post('/login',[Usercontroller::class,'userLoggedIn'])->middleware('guest');
Route::post('/logout',[Usercontroller::class,'userLogOut'])->middleware('user.logged.in');
Route::get('/create-post',[PostController::class,'createPostView'])->middleware('user.logged.in');
Route::post('/create-post',[PostController::class,'createPost'])->middleware('user.logged.in');
Route::get('/posts/{post}',[PostController::class,'showPost'])->middleware('user.logged.in');
Route::DELETE('/posts/{post}',[PostController::class,'deletePost'])->middleware('can:delete,post');
Route::get('/posts/{post}/edit',[PostController::class,'editPost'])->middleware('can:update,post');
Route::PUT('/posts/{post}',[PostController::class,'updatePost'])->middleware('can:update,post');
Route::get('/upload-avatar',[Usercontroller::class,'uploadAvatarView'])->middleware('user.logged.in');
Route::post('/upload-avatar',[Usercontroller::class,'uploadAvatar'])->middleware('user.logged.in');
Route::post('/follow/{user:username}',[FollowController::class, 'getFollow'])->middleware('user.logged.in');
Route::post('/remove/{user:username}',[FollowController::class, 'removeFollow'])->middleware('user.logged.in');
Route::get('/profile/{user:username}',[Usercontroller::class,'profileView'])->middleware('user.logged.in');

Route::get('/profile/{user:username}/followers',[Usercontroller::class,'profileFollowersView'])->middleware('user.logged.in');

Route::get('/profile/{user:username}/following',[Usercontroller::class,'profileFollowingView'])->middleware('user.logged.in');

Route::get('/search/{term}',[PostController::class, 'searchPost'])->middleware('user.logged.in');
Route::post('/chat-message', function (Request $request) {
    $formField = $request->validate([
        'textValue' => 'required',
    ]);
if(!trim(strip_tags($formField['textValue']))) {
    return response()->noContent();
}
broadcast(new ChatMessage([
    'username'=>auth()->user()->username,
    'textValue'=>$formField['textValue'],
    'avatar'=>auth()->user()->avatar
]))->toOthers();
return response()->noContent();
})->middleware('user.logged.in');
Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
    Route::get('/profile/{user:username}/raw', [Usercontroller::class, 'profileViewRaw']);
    Route::get('/profile/{user:username}/followers/raw', [Usercontroller::class, 'profileFollowersViewRaw']);
    Route::get('/profile/{user:username}/following/raw', [Usercontroller::class, 'profileFollowingViewRaw']);
});
