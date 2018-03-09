<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Purchase;

class ProductController extends Controller
{
    public function getAllPurchases()
    {
        // https://laravel.com/docs/5.6/queries#joins
        $purchases = DB::table('purchase')
        ->join('product', 'purchase.ID', '=', 'product.ID')
        ->join('producttype', 'product.ProductType', '=', 'producttype.ID')
        ->join('unit', 'product.Unit', '=', 'unit.ID')
        ->join('payment', 'purchase.Payment', '=', 'payment.ID')
        ->join('store', 'payment.Store', '=', 'store.ID')
        ->join('paymenttype', 'payment.PaymentType', '=', 'paymenttype.ID')
        ->join('bank', 'payment.Bank', '=', 'bank.ID')
        ->select('purchase.ID', 'producttype.Name as Product', 'product.Name as Name',
            'PackSize', 'unit.Name as Unit', 'UnitPrice', 'Qty', 'Date',
            'store.Name as Store', 'paymenttype.Name as PaymentType', 'bank.Name as Bank')
        ->get();

        return response()->json([
            'message' => 'success',
            'data' => $purchases
        ], 200);
    }

    public function getCart()
    {
        $cart = DB::table('purchases')
        ->where('Payment', 0)
        ->get();

        return response()->json([
            'message' => 'success',
            'data' => $cart
        ], 200);
    }

    public function addToCart(Request $request)
    {
        // https://laravel.com/docs/5.6/eloquent#inserting-and-updating-models
        // https://stackoverflow.com/questions/21219482/posting-json-to-laravel#answer-33298205
        $cart = Purchase::create($request->json()->all());
        
        return response()->json($cart, 201);
    }

    public function removeCart(Request $request)
    {
        $cartItems = DB::table('purchases')
        ->where('Payment', 0)
        ->get()
        ->toArray();
        
        DB::table('purchases')
        ->where('Payment', 0)
        ->delete();

        return response()->json([
            'message' => 'Product has been removed from cart.',
            'data' => $cartItems
        ], 201);
    }
}
