<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class MultiStepForm extends Component
{
    public $step = 1; // Paso actual del formulario
    public $selectedProducts = [];
    public $total = 0;
    public $location = null; // Para almacenar la ubicación del usuario
    public $userInfo = [
        'name' => '',
        'email' => '',
        // otros datos si es necesario
    ];

    public function loadProducts()
    {
        // Cargar productos y agruparlos por la columna 'category'
        $categories = Product::all()->groupBy('category');
        return $categories;
    }


    // Paso 2: Seleccionar productos
    public function selectProduct($productId)
    {
        if (in_array($productId, $this->selectedProducts)) {
            $this->selectedProducts = array_diff($this->selectedProducts, [$productId]);
        } else {
            $this->selectedProducts[] = $productId;
        }

        // Actualizar el total
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = Product::whereIn('id', $this->selectedProducts)
                              ->sum('price');
    }

    // Paso 3: Mapa de ubicación
    public function setLocation($lat, $lng)
    {
        $this->location = ['lat' => $lat, 'lng' => $lng];
    }

    // Paso 4: Resumen de la orden
    public function submitOrder()
    {
        // Aquí guardarías la orden en la base de datos, etc.
        $this->step = 5;
    }

    public function render()
    {
        return view('livewire.multi-step-form', [
            'categories' => $this->loadProducts(),
        ]);
    }
}
