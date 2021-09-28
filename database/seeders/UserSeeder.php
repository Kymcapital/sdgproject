<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
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
        
        $users = [
            [
                'id' => 1,
                'first_name' => "Jackson",
                'last_name' => "Chegenye",
                'email' => "jackson.chegenye@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 1, //Super Admin
                'user_id' => 1,
                'permission_id' => 1,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ],
            [
                'id' => 2,
                'first_name' => "Johnson",
                'last_name' => "Gitonga",
                'email' => "johnson.gitonga@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 2, //Admin
                'user_id' => 1,
                'permission_id' => 1,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ],
            [
                'id' => 3,
                'first_name' => "Ambrose",
                'last_name' => "Ebale",
                'email' => "ambrose.ebale@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 2, //Admin
                'user_id' => 1,
                'permission_id' => NULL,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ],
            [
                'id' => 4,
                'first_name' => "Alfred",
                'last_name' => "Maina",
                'email' => "alfred.maina@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 3, //Employee
                'user_id' => 1,
                'permission_id' => 1, //Manage Reviews,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ],
            [
                'id' => 5,
                'first_name' => "Anthony",
                'last_name' => "Kieti",
                'email' => "anthony.kieti@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 3, //Employee
                'user_id' => 1,
                'permission_id' => NULL,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ],
            [
                'id' => 6,
                'first_name' => "Aaron",
                'last_name' => "Kandia",
                'email' => "aaron.kandia@oxygene.co.ke",
                'company_id' => 1,
                'role_id' => 3, //Employee
                'user_id' => 1,
                'permission_id' => NULL,
                'division_id' => 1,
                'created_at' => $createdDate,
                'updated_at' => $createdDate
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                $user
            );
        }
    }
}
