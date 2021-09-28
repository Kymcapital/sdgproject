<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {
        $date = Carbon::now();
        $createdDate = clone($date);

        $roles = [            
            ['id' => 1, 'name' => 'Super Admin', 'description' => "A super admin user has the ability to manage all the system's functionalities.", 'created_at' => $createdDate, 'updated_at' => $createdDate],
            ['id' => 2, 'name' => 'Admin', 'description' => "An admin has the ability to manage a few of the system's functionalities.", 'created_at' => $createdDate, 'updated_at' => $createdDate],
            ['id' => 3, 'name' => 'Employee', 'description' => "An employee has the ability to manage ...", 'created_at' => $createdDate, 'updated_at' => $createdDate],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }

    }
}
