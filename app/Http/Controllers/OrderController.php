<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = order::all();
        foreach ($orders as $order) {
            $order->order = is_string($order->order) ? json_decode($order->order) : $order->order;
        }
        try {
            return $orders;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_email' => 'required|email',
            'adress' => 'required',
            'order_status' => 'nullable',
            'order' => 'required|json',
            'user_id' => 'required|numeric',
            'vendor_id' => 'nullable|numeric',
            'category_id' => 'nullable|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        try {
            $order = new order();
            $order->user_email = $request->user_email;
            $order->adress = $request->adress;
            $order->order_status = $request->order_status;
            $order->order = $request->order;
            $order->user_id = $request->user_id;
            $order->vendor_id = $request->vendor_id;
            $order->category_id = $request->category_id;
            $order->save();
            return $order ;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required'
        ]);
        try {
            $order = order::findOrFail($id);
            $order->order_status = $request->order_status;
            $order->save();
            return $order ;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    try {
        $order = Order::findOrFail($id);
        $order->order = json_decode($order->order, true);
        
        return response()->json([
            'data' => $order
        ], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Order not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 400);
    }
}





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = order::findOrFail($id);
            $order->delete();
            return response()->json([
                'message' => 'order is deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }
}
