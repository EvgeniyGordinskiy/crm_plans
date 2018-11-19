<?php

use App\Models\InviteType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MessagesTemplates::create([
            'name' => InviteType::PLAN,
            'body' => 'Hello %recipient_name%, you have been invited to the plan %plan_name%!'
        ]);
        InviteType::create(['name' => InviteType::PLAN]);    }
}
