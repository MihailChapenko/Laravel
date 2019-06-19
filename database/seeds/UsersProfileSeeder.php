<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_profile')->insert([
            [
                'user_id' => 1,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'address' => 'secret',
                'phone' => '123123',
                'is_admin' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
