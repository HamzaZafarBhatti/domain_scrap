<?php

namespace Database\Seeders;

use App\Models\DomainTld;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomainTldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DomainTld::create(['name' => '.com']);
        DomainTld::create(['name' => '.net']);
        DomainTld::create(['name' => '.org']);
        DomainTld::create(['name' => '.de']);
    }
}
