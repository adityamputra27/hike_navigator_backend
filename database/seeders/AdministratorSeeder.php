<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrator = new \App\Models\User;
        $administrator->username = 'admin';
        $administrator->name = 'Administrator';
        $administrator->email = 'test@mail.com';
        $administrator->role = 'ADMIN';
        $administrator->password = \Hash::make('admin123');
        $administrator->avatar = 'default.png';
        $administrator->address = 'Cianjur, Jawa Barat';
        $administrator->phone = '081222534937';
        $administrator->save();

        $this->command->info('User Admin berhasil diinsert');
    }
}
