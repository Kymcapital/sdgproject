<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
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
            [
                'id' => 1,
                'name' => "Manage Reviews",
                'description' => "User has the ability to edit,delete,approve reviews ...",
                'permission_id' => 1,
                'created_at' => $createdDate, 
                'updated_at' => $createdDate
            ]
        ];

        foreach ($roles as $role) {
            Permission::updateOrCreate(
                ['id' => $role['id']],
                $role
            );
        }
    }
}
