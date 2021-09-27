<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::create([
      'first_name'     =>  'Vijay',
      'email'   =>  'kvjkumr@gmail.com', 
      'active'  =>  1,
      'password'=>  bcrypt('123456'),
    ]);
    $user->assignRole(1);

    $user = User::create([
      'first_name' =>  'Vijay',
      'email'   =>  'admin@gmail.com', 
      'active'  =>  1,
      'password'=>  bcrypt('123456'),
    ]);
    $user->assignRole(2);
    $user->assignSite(1);
  }
}
