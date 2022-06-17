<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FriendRequestController extends Controller
{    
    /**
     * Asking for friend request to a user.
     *
     * @param  Request $request
     * @return Response
     */
    public function askFriendRequest(Request $request)
    {
        try {
            if ($request->user_email == $request->requestor_email) {
                return response(null, 409);
            }

            $friendRequest = User::where('id', '=', $request->user_id)
                                 ->whereNull('blocked')
                                 ->first();

            if ($friendRequest) {
                $friendRequest->requests()
                              ->firstOrCreate([
                                    'user_id' => $friendRequest->id,
                                    'requestor_id' => $request->requestor_id,
                                    'user_email' => $friendRequest->email,
                                    'requestor_email' => $request->requestor_email,
                                ], ['status' => 'pending',]);

                return ApiFormatter::createApi(201, true, 'Friend request has been sent.');
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to send friend request.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
        
    /**
     * Accept a friend request.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function acceptFriendRequest(Request $request, $id)
    {
        try {
            Friend::firstOrCreate([
                'user_id' => $request->requestor_id,
                'friend_id' => $request->user_id,
                'user_email' => $request->requestor_email,
                'friend_email' => $request->user_email,
            ]);

            $friendRequest = User::findOrFail($request->user_id)
                                 ->requests()
                                 ->findOrFail($id)
                                 ->update(
                                     ['status' => 'accepted']
                                 );
            
            if ($friendRequest) {
                return ApiFormatter::createApi(201, true, 'Your friend`s request has been accepted.');
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to accept friend`s request.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
    
    /**
     * Reject a friend request.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function rejectFriendRequest(Request $request, $id)
    {
        try {
            $friendRequest = User::findOrFail($request->user_id)->requests()
                                 ->findOrFail($id)
                                 ->update(
                                     ['status' => 'rejected']
                                 );
            
            if ($friendRequest) {
                return ApiFormatter::createApi(201, true, 'Your friend`s request has been rejected.');
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to reject friend`s request.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
    
    /**
     * Show all friend requests of a user.
     *
     * @param  Request $request
     * @return Response
     */
    public function showAllRequests(Request $request)
    {
        try {
            $data = FriendRequest::where('user_email', '=', $request->user_email)
                                 ->get(['requestor_email', 'status']);
            
            if ($data) {
                $jsonArr = array(
                    'message' => 'The data have been loaded successfully.',
                    'requests' => $data,
                );

                return ApiFormatter::createApi(200, true, $jsonArr);
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to show list of friend requests.');
            }

        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
    
    /**
     * Show all friends of a user.
     *
     * @param  Request $request
     * @return Response
     */
    public function showAllFriends(Request $request)
    {
        try {
            $data = Friend::where('user_email', '=', $request->user_email)
                          ->pluck('friend_email');

            if ($data) {
                $jsonArr = array(
                    'message' => 'The data have been loaded successfully.',
                    'friends' => $data,
                );

                return ApiFormatter::createApi(200, true, $jsonArr);
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to show list of friends.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
    
    /**
     * Show all common friends based on users.
     *
     * @param  Request $request
     * @return Response
     */
    public function showCommonFriends(Request $request)
    {
        try {
            $subquery = DB::table('friends')->whereIn('user_email', $request->friends)
                          ->groupBy('friend_id')
                          ->having(DB::raw('count(friend_id)'), '>=', 2)
                          ->pluck('friend_id');

            $data = User::whereIn('id', $subquery)
                        ->pluck('email')
                        ->toArray();
                        
            $count = count($data);

            if ($data) {
                $jsonArr = array(
                    'message' => 'The data have been loaded successfully.',
                    'friends' => $data,
                    'count' => $count,
                );

                return ApiFormatter::createApi(200, true, $jsonArr);
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to show list of common friends.');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
    
    /**
     * Blok a specific user.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function blockUser(Request $request)
    {
        try {
            $blockedUser = User::where('email', '=', $request->block)
                               ->first();
            $blockedUser->blocked = $request->requestor;
            $blockedUser->save();

            Friend::where('user_email', '=', $request->requestor)
                  ->where('friend_email', '=', $request->block)
                  ->delete();

            if ($blockedUser) {
                return ApiFormatter::createApi(200, true, "The user has been blocked successfully.");
            } else {
                return ApiFormatter::createApi(400, false, 'Failed to show list of friends.');
            }

        } catch (Exception $error) {
            return ApiFormatter::createApi(400, false, $error);
        }
    }
}
