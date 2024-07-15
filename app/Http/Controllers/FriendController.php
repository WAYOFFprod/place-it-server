<?php

namespace App\Http\Controllers;

use App\Enums\FriendRequestStatus;
use App\Http\Requests\RequestFriendRequest;
use App\Http\Requests\RespondFriendRequest;
use App\Http\Resources\FriendResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function requestFriend(RequestFriendRequest $request) {
        $user = Auth::user();

        if($user->id == $request->friend_id) {
            return response()->json([
                'message'=> 'can\'t add yourself'
            ], 400);
        }

        if($user->friends()->find($request->friend_id)) {
            return response()->json([
                'message'=> 'already friends'
            ], 400);
        }

        if($user->pendingFriendsTo()->where('friend_id', $request->friend_id)->count() > 0) {
            return new FriendResource($user->pendingFriendsTo()->where('friend_id', $request->friend_id)->first());
            return response()->json([
                'message'=> 'request already sent'
            ], 400);
        }

        if($user->pendingFriendsFrom()->where('user_id',$request->friend_id)->count() > 0) {
            $friend = $user->pendingFriendsFrom()->where('user_id',$request->friend_id)->first();
            $friend->pivot->status = FriendRequestStatus::Accepted->value;
            $friend->pivot->save();
            return response()->json([
                'message'=> 'you are now friends with '.$friend->name
            ], 201);
        }

        $user->friendsTo()->attach($request->friend_id, ['status' => FriendRequestStatus::Pending->value]);

        $friend = $user->friendsTo()->where('friend_id', $request->friend_id)->first();

        return new FriendResource($friend);
    }

    public function acceptFriend(RespondFriendRequest $request) {
        $user = Auth::user();
        $friend = $user->pendingFriendsFrom()->where('user_id', $request->friend_id)->first();
        if(empty($friend)) {
            return response()->json([
                'message'=> 'no request with user '.$request->friend_id
            ], 400);
        }
        $friend->pivot->status = FriendRequestStatus::Accepted->value;
        $friend->pivot->save();
        return new FriendResource($friend);
    }

    public function rejectFriend(RespondFriendRequest $request) {
        $user = Auth::user();
        $friend = $user->pendingFriendsFrom()->where('user_id',$request->friend_id)->first();
        if(empty($friend)) {
            return response()->json([
                'message'=> 'no request with user '.$request->friend_id
            ], 400);
        }
        $friend->pivot->status = FriendRequestStatus::Rejected->value;
        $friend->pivot->save();
        return new FriendResource($friend);
    }

    // get requests from other users to current user
    public function getRequests(Request $request) {
        $user = Auth::user();
        return FriendResource::collection($user->pendingFriendsFrom);
    }

    // returns requests from others, your own request and accepted friends
    public function getFriends(Request $request) {
        $user = Auth::user();
        return FriendResource::collection($user->friends);
    }
}
