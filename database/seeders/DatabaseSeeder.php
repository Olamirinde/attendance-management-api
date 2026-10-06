<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminModel;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        AdminModel::firstOrCreate(
            ['email' => 'amos@admin.com'],
            [
                'name' => 'Adekunle Amos',
                'password' => Hash::make('secret123'),
            ]
        );
    }
}