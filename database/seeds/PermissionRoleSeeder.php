<?php

use Illuminate\Database\Seeder;
use App\Role;

class PermissionRoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Super Admin Permissions
    $role = Role::find(1);
    $role->assignpermission(1);
    $role->assignpermission(2);
    $role->assignpermission(3);
    $role->assignpermission(4);
    $role->assignpermission(5);
    $role->assignpermission(6);
    $role->assignpermission(7);
    $role->assignpermission(8);
    $role->assignpermission(9);
    $role->assignpermission(10);
    $role->assignpermission(11);

    // Main Admin Permissions
    $role = Role::find(2);
    $role->assignpermission(1);
    $role->assignpermission(2);
    $role->assignpermission(3);
    $role->assignpermission(4);
    $role->assignpermission(5);
    $role->assignpermission(6);
    $role->assignpermission(7);
    $role->assignpermission(8);
    $role->assignpermission(9);
    $role->assignpermission(10);
    $role->assignpermission(11);

    // Admin Permissions
    $role = Role::find(3);
    $role->assignpermission(1);
    $role->assignpermission(2);
    $role->assignpermission(3);
    $role->assignpermission(4);
    $role->assignpermission(5);
    $role->assignpermission(6);
    $role->assignpermission(7);
    $role->assignpermission(8);
    $role->assignpermission(9);
    $role->assignpermission(10);
    $role->assignpermission(11);
  }
}
