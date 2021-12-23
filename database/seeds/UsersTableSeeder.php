<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    User::create([
      'username' => 'test',
      'email' => 'test@gmail.com',
      'password' => bcrypt('testtest')
    ]);

    User::create([
      'username' => '管理者',
      'email' => 'admin@gmail.com',
      'password' => bcrypt('adminadmin'),
      'role' => 1
    ]);
  }
}
