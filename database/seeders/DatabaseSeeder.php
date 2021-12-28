<?php

namespace Database\Seeders;

use App\Models\ChatProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory(4)->create();

        $user[0]->friendsOfMine()->toggle(['friend_id' => $user[1]->id]);
        $user[0]->friendsOfMine()->wherePivot('friend_id', '=',  $user[1]->id)->update(['accepted' => 1]);   
        $user[2]->friendsOfMine()->toggle(['friend_id' => $user[3]->id]);
        $user[2]->friendsOfMine()->wherePivot('friend_id', '=',  $user[3]->id)->update(['accepted' => 1]);

        $user[0]->messagesSent()->create(['receiver_id' => $user[1]->id, 'message' => 'test123']);
        sleep(1);
        $user[1]->messagesSent()->create(['receiver_id' => $user[0]->id, 'message' => 'response123']);

        $user[2]->messagesSent()->create(['receiver_id' => $user[3]->id, 'message' => 'test123 #2 other users']);
        sleep(1);
        $user[3]->messagesSent()->create(['receiver_id' => $user[2]->id, 'message' => 'response123 #2 other users']);
    }
}
