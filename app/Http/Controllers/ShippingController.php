<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function get_cost(Request $request)
    {
        $request->validate([
            'origin' => ['required'], // ID KOTA ATAU SUBDISTRICT
            'originType' => ['required', 'in:city,subdistrict'],
            'destination' => ['required'], // ID KOTA ATAU SUBDISTRICT
            'destinationType' => ['required', 'in:city,subdistrict'],
            'weight' => ['required', 'integer'],
            'courier' => ['string']
        ]);

        $response = Http::withHeaders([
            'key' => env("RAJAONGKIR_KEY")
        ])
        ->post(env('RAJAONGKIR_URL') . 'cost', [
            'origin' => $request->origin,
            'originType' => $request->originType,
            'destination' => $request->destination,
            'destinationType' => $request->destinationType,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ])
        ->json();

        return $response;
    }

    public function get_waybill(Request $request){
        $request->validate([
            'waybill' => ['required', 'string'],
            'courier' => ['required', 'string'],
        ]);

        $response = Http::withHeaders([
            'key' => env("RAJAONGKIR_KEY")
        ])
        ->post(env('RAJAONGKIR_URL') . 'waybill', [
            'waybill' => $request->waybill,
            'courier' => $request->courier,
        ])
        ->json();
        return $response;
    }
}
