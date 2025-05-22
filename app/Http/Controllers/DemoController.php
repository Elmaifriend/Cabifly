<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DemoController extends Controller
{
    public function checkDemoStatus()
    {
        // Obtener el estado actual (default: false)
        $startDemo = Cache::get('start_demo', false);

        // Preparar la respuesta ANTES de cambiar el estado
        $response = response()->json(['start_demo' => $startDemo]);

        // Si el estado era true, lo cambiamos a false PARA LA PRÓXIMA PETICIÓN
        if ($startDemo) {
            Cache::put('start_demo', false, now()->addMinutes(10)); // Persistencia temporal
        }

        // Devolver la respuesta con el valor original (true/false)
        return $response;
    }

    public function activateDemo()
    {
        // Activar el demo (para la próxima petición)
        Cache::put('start_demo', true, now()->addMinutes(10));
        return response()->json(['success' => true]);
    }
}
