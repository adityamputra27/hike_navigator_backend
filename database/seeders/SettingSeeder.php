<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = new \App\Models\Setting;
        $setting->name = 'Hike Navigator - GPS Location for Hiker';
        $setting->version = '1.0.0';
        $setting->android_link = '-';
        $setting->ios_link = '-';
        $setting->android_package = '-';
        $setting->ios_package = '-';
        $setting->address = 'Solo, Jawa Tengah, Indonesia';
        $setting->save();

        $this->command->info('Mobile app setting berhasil diinsert');
    }
}
