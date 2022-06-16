<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_request_friend()
    {   
        $dummyUsers = User::where('email', '=', 'dummy@gmail.com')
                          ->orWhere('email', '=', 'user@gmail.com')
                          ->whereNull('blocked')
                          ->get();
        $friend_request = FriendRequest::where('user_email', '=', 'dummy@gmail.com')
                                       ->where('requestor_email', '=', 'user@gmail.com')
                                       ->first();
        
                                       
        if ($friend_request) {
            $friend_request->delete();
        }

        $user = $dummyUsers[0];
        $requestor = $dummyUsers[1];
        $formData = [
            'user_id' => $user->id,
            'requestor_id' => $requestor->id,
            'user_email' => $user->email,
            'requestor_email' => $requestor->email,
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('request'), $formData);
        $response->assertStatus(201);
    }

    public function test_can_accept_request()
    {
        $friend_request = FriendRequest::where('user_email', 'like', 'dummy%')
                                       ->where('requestor_email', 'like', 'user%')
                                       ->first();
        
        $formData = [
            'user_id' => $friend_request->user_id,
            'requestor_id' => $friend_request->requestor_id,
            'user_email' => $friend_request->user_email,
            'requestor_email' => $friend_request->requestor_email, 
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('accept_request', $friend_request->id), $formData);
        $response->assertStatus(201);
    }
    
    public function test_can_reject_request()
    {
        $friend_request = FriendRequest::where('user_email', 'like', 'dummy%')
                                       ->where('requestor_email', 'like', 'user%')
                                       ->first();

        if ($friend_request->status == 'accepted') {
            Friend::where('user_id', '=', $friend_request->user_id)
                  ->where('friend_id', '=', $friend_request->requestor_id)
                  ->delete();
            $friend_request->update(['status' => 'pending']);
        }

        $formData = [
            'user_id' => $friend_request->user_id,
            'requestor_id' => $friend_request->requestor_id,
            'user_email' => $friend_request->user_email,
            'requestor_email' => $friend_request->requestor_email, 
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('reject_request', $friend_request->id), $formData);
        $response->assertStatus(201);
    }

    public function test_can_show_all_requests()
    {
        $dummyEmail = User::where('email', '=', 'dummy@gmail.com')
                          ->pluck('email');
        
        $formData = [
            'user_email' => $dummyEmail,
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('show_requests'), $formData);
        $response->assertStatus(200);
    }

    public function test_can_show_users_friends()
    {
        $dummyEmail = User::where('email', '=', 'dummy@gmail.com')
                          ->pluck('email');

        $formData = [
            'user_email' => $dummyEmail,
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('show_friends'), $formData);
        $response->assertStatus(200);
    }

    public function test_can_show_common_friends()
    {
        $formData = [
            'friends' => [
                'hilmiaji@gmail.com',
                'rifqi@gmail.com'
            ]
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('mutual_friends'), $formData);
        $response->assertStatus(200);
    }

    public function test_can_block_user()
    {
        $formData = [
            'requestor' => 'dummy@gmail.com',
            'block' => 'user@gmail.com'
        ];

        $this->withoutExceptionHandling();
        $response = $this->post(route('block_user'), $formData);
        $response->assertStatus(200);
    }
}
