<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;


class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 20 órdenes, cada una con 3 productos asociados
        Order::factory()
            ->count(20)
            ->withProducts(3)
            ->create();
    }
}
