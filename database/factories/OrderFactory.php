<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition(): array
    {
        return [
            'total' => $this->faker->randomFloat(2, 50, 1000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }

    // Método para adjuntar productos a la orden después de crearla
    public function withProducts($count = 3): self
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $products = \App\Models\Product::factory()->count($count)->create();
            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $this->faker->numberBetween(1, 5),
                    'unit_price' => $product->price,
                ]);
            }
        });
    }
}
