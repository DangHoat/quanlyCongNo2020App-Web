<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate(); // Using truncate function so all info will be cleared when re-seeding.
		DB::table('roles')->truncate();
		DB::table('role_users')->truncate();
		DB::table('activations')->truncate();

		$admin = Sentinel::registerAndActivate([
            'name' => 'Admin',
            'email' => 'nnmanh@uneti.edu.vn',
            'password' => '123456',
        ]);

		$adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Admin',
			'slug' => 'admin',
			'permissions' => array('admin' => 1),
		]);

		$userRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Staff',
			'slug'  => 'staff',
		]);

		$admin->roles()->attach($adminRole);

		$this->command->info('Admin User created with username admin@admin.com and password 123456');
    }
}
