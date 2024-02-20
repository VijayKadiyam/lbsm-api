<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Role::truncate();
    // Role::create(['name' => 'Super Admin']);
    // Role::create(['name' => 'Main Admin']);
    // Role::create(['name' => 'Admin']);
    // Role::create(['name' => 'User']);
    // Role::create(['name' => 'Candidate']);
    // Role::create(['name' => 'Master']);

    $Role_array = [
      'Super Admin',
      'Main Admin',
      'Admin',
      'User',
      'Candidate',
      'Master',
      'Acting Admin',
    ];
    foreach ($Role_array as $name) {
      $role = Role::where('name', '=', $name)
        ->first();
      if ($role == '' || $role == null) {
        $role = Role::create([
          'name'   =>  $name,
        ]);
      }
    }
  }
}
