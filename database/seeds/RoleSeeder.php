<?php
 
use Illuminate\Database\Seeder;
use App\Models\Role;
 
class RoleSeeder extends Seeder{
 
    public function run(){
        DB::table('roles')->delete();
 
        Role::create([
            'name' => 'user'
        ]);
 
        Role::create([
            'name' => 'administrator'
        ]);
 
    }
}