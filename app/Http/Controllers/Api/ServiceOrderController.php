<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceOrderController extends Controller
{
    public function index()
    {
        $orders = ServiceOrder::with('user:id,name')->get();

        return response()->json($orders->map(function ($order) {
            return [
                'id' => $order->id,
                'vehiclePlate' => $order->vehiclePlate,
                'entryDateTime' => $order->entryDateTime,
                'exitDateTime' => $order->exitDateTime,
                'priceType' => $order->priceType,
                'price' => $order->price,
                'userName' => $order->user->name,
            ];
        }));
    }

    public function store(Request $request)
{

    try {
        $data = $request->validate([
            'vehiclePlate'   => 'required|string|size:7',
            'entryDateTime'  => 'required|date_format:Y-m-d H:i:s',
            'exitDateTime'   => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:entryDateTime',
            'priceType'      => 'nullable|string|max:55',
            'price'          => 'nullable|numeric|min:0|max:9999999999.99',
            'userId'         => 'required|integer|exists:users,id',
        ]);


        ServiceOrder::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Ordem de serviço criada com sucesso',
            'data' => $data
        ], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $e->errors()
        ], 422);
    }
}
}

