<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food = Food::all();
        return $food;
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

        if ($user->role == 'admin') {
            $request->validate([
                "name" => 'required',
                "description" => 'required',
                "price" => 'required',
                "picture" => 'required'
            ]);

        $table = Food::create([
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "picture" => $request->picture
        ]);

        return response()->json([
            'success' => 201,
            'message' => 'Food saved successfully',
            'data' => $table
        ], 201);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'You dont have access to this.',
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
        $food = Food::find($id);
        if ($food) {
            return response()->json([
                'status' => 200,
                'data' => $food
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
        $user = auth()->user();
        if ($user->role == 'admin') {
            $request->validate([
                "name" => 'required',
                "description" => 'required',
                "price" => 'required',
                "picture" => 'required'
            ]);

            $food = Food::find($id);

            $food->update([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "picture" => $request->picture
            ]);

             return response()->json([
                'status' =>'success',
                'message' => 'Food successfully updated',
                'data' => $food
            ], 201);
        } else {
            return response()->json([
                'status' =>'error',
                'message' => 'You dont have access to update food',
            ], 401);
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
        $user = auth()->user();
        if ($user->role == 'admin') {
            $food = Food::find($id);
            $food->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'food successfully erased',

            ], 200);
        } else{
            return response()->json([
                'status' =>'error',
                'message' => 'You dont have access to erase food',
            ], 401);
        }
    }
}