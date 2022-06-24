<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			'name' => 'John Doe',
			'email' => 'admin@example.com',
			'password' => bcrypt('admin'),
			'role' => 'admin',
			'created_at' => now(),
			'updated_at' => now()
		]);
    }
}
