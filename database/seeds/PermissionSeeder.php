<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Permission::truncate();
    Permission::create(['name' => 'Manage Dashboard']); // 1
    Permission::create(['name' => 'Manage Sites']); // 2
    Permission::create(['name' => 'Manage Users']); // 3
    Permission::create(['name' => 'Manage Permissions']); // 4
    Permission::create(['name' => 'Manage Values']); // 5
    Permission::create(['name' => 'Manage Value Lists']); // 6
    Permission::create(['name' => 'Manage Programs']); // 7
    Permission::create(['name' => 'Manage Program Posts']); // 8
    Permission::create(['name' => 'Manage User Programs']); // 9
    Permission::create(['name' => 'Manage User Program Posts']); // 10
    Permission::create(['name' => 'Manage User Program Tasks']); // 11
  }
}
