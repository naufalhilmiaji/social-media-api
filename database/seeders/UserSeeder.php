<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)
            ->has(FriendRequest::factory()->count(3), 'friend_requests')
            ->create();
    }
}
