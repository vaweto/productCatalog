<?php

namespace Database\Seeders;

use App\Models\ExternalSuppliers;
use Illuminate\Database\Seeder;

class ExternalSuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExternalSuppliers::create([
            'name' => 'httpbin.org',
            'url' => 'http://httpbin.org/post',
        ]);
    }
}
