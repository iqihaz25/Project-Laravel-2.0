<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Food;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::all();

        return response()->json([
            'message' => 'Orders successfully shown',
            'data' => $order
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $food = Food::where('id', $request->food_id)->first();
        

        if($user->role == 'user') 
        {
            $request->validate([
            'order_address' => 'required',
            'order_amount' => 'required',
            'payment_method'=> 'required',
            'date' => 'required',
         
            ]);

            $order = Order::create([
                "order_address" => $request->order_address,
                "order_amount" => $request->order_amount,
                "payment_method" => $request->payment_method,
                "date" => $request->date,
                "total_amount" => $request->order_amount * $food->price 
            ]);

            return response()->json([
                'status' =>'success',
                'message' => 'Order successfully added',
                'data' => $order
            ], 201);
        } else {
            return response()->json([
                'status' =>'error',
                'message' => 'You dont have access to place an order',
            ], 401);
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if ($order) {
            return response()->json([
                'status' => 200,
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'ID ' . $id . ' not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if($order){
            $order->order_address = $request->order_address ? $request->order_address : $order->order_address;
            $order->order_amount = $request->order_amount ? $request->order_amount : $order->order_amount;
            $order->payment_method = $request->payment_method ? $request->payment_method : $order->payment_method;
            $order->date = $request->date ? $request->date : $order->date;
            $order->total_amount = $request->total_amount ? $request->total_amount : $order->total_amount;
            $order->save();
            return response()->json([
                'status' => 200,
                'data' => $order
            ],200);
        } else {
            return response()->json([
                'status'=>404,
                'message'=> $id . ' not found'
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::where('id',$id)->first();
        if($order){
            $order->delete();
            return response()->json([
                'status' =>200,
                'data'=> $order
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'ID' . $id . ' not found'
            ],404);
        }
    }
}