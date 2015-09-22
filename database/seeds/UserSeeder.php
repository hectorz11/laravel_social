<?php
 
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
 
class UserSeeder extends Seeder{
 
    public function run()
    {
        DB::table('users')->delete();
 
        $adminRole = Role::whereName('administrator')->first();
        $userRole = Role::whereName('user')->first();
 
        $user = User::create(array(
            'first_name' => 'Hector Raul',
            'last_name' => 'Zapana Condori',
            'email' => 'hecza@yopmail.com',
            'password' => Hash::make('123456'),
        ));
        $user->assignRole($adminRole);
 
        $user = User::create(array(
            'first_name' => 'Wilber',
            'last_name' => 'Zapana Condori',
            'email' => 'wilza@yopmail.com',
            'password' => Hash::make('123456'),
        ));
        $user->assignRole($userRole);
    }
}