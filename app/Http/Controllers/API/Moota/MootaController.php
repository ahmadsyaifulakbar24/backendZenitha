<?php

namespace App\Http\Controllers\API\Moota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MootaController extends Controller
{
    public function bank()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env("MOOTA_API_TOKEN")
        ])->get(env("MOOTA_URL") . 'bank')
        ->json();

        return $response;
    }
}
