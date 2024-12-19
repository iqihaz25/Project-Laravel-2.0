<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\User;
use App\Models\Payment;
use App\Models\Order;

class OrderPayment extends Controller
{
    public function index()
    {
        $order = Payment::all();
        return $order;
    }

    public function store(Request $request)
    {               
           

            if($request->bayar < $request->total)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You dont have enough money???',
                ], 200);
            }else{
                $order = Payment::create([
                    'date' => $request->date,
                    'id_payment' => $request->id_payment,
                    'id_order' => $request->id_order,
                    'total' => $request->total,
                    'bayar' => $request->bayar,
                    'kembali' => $request->bayar - $request->total,
                    'status' => 'pending',
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order successfully added',
                'data' => $order
            ], 200);
    }

    public function show($id) 
    {
        $order = Payment::find($id);
        if ($order) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Order successfully shown',
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The ID above ' . $id . ' is not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $order = Payment::find($id);
        $order->delete();
        if ($order) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Order successfully deleted',
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'The ID above ' . $id . ' is not found'
            ], 404);
        }
    }
}

