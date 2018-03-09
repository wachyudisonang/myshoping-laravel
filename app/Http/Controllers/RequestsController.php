<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Purchase;

class RequestsController extends Controller
{
	public function getEntity(Request $request)
	{
		$msg = "no result found";
		$data = [];

		$reqType = '\App\\' . $request->type;
		if (class_exists($reqType)) {
			$model = new $reqType;
			$data = DB::table($model->getTable())->get();
		}

		// https://laravel.com/docs/5.6/queries#joins

		if($request->type == "purchases") {
			$data = DB::table($request->type)
			->join('products', 'purchases.ID', '=', 'products.ID')
			->join('product_types', 'products.ProductType', '=', 'product_types.ID')
			->join('unit', 'products.Unit', '=', 'unit.ID')
			->join('payment', 'purchases.Payment', '=', 'payment.ID')
			->join('store', 'payment.Store', '=', 'store.ID')
			->join('paymenttype', 'payment.PaymentType', '=', 'paymenttype.ID')
			->join('banks', 'payment.Bank', '=', 'banks.ID')
			->select('purchases.ID', 'product_types.Name as Product', 'products.Name as Name',
				'PackSize', 'unit.Name as Unit', 'UnitPrice', 'Qty', 'Date',
				'store.Name as Store', 'paymenttype.Name as PaymentType', 'banks.Name as Bank')
			->get();
		}

		if (!empty($data)) {
			$msg = "success";
		}

		return response()->json([
			'message' => $msg,
			'data' => $data
		], 200);
	}

	public function filterEntity(Request $request)
	{
		$msg = "no result found";
		$data = [];
		
		$reqType = '\App\\' . $request->type;
		if (class_exists($reqType)) {
			$model = new $reqType;
			$data = DB::table($model->getTable())
			->where('Key', $request->key)
			->get();
		}
		
// dd($data);
		if (!empty($data)) {
			$msg = "success";
		}

		return response()->json([
			'message' => $msg,
			'data' => $data
		], 200);
	}

	public function addEntity(Request $request, $type)
	{
		// https://laravel.com/docs/5.6/eloquent#inserting-and-updating-models
		// https://stackoverflow.com/questions/21219482/posting-json-to-laravel#answer-33298205
		// https://laracasts.com/discuss/channels/general-discussion/l5-calling-dynamic-controller-names
		// https://laravel.com/docs/5.6/requests#accessing-the-request

		$reqType = '\App\\' . $type;
		$model = new $reqType;

		$response =$model::create($request->json()->all());
		
		return response()->json($response, 201);
	}

	public function editEntity(Request $request)
	{
		$reqType = '\App\\' . $request->type;
		$model = new $reqType;

		// http://php.net/manual/en/function.array-splice.php
		$khhj = $request->all();
		$jhgghg = array_splice($khhj, 1);
		// // dd(array_splice($khhj, 1));

		$filter = DB::table($model->getTable())
		->where('Key', $request->key)
		->update($jhgghg);

		// dd($filter);

		return response()->json([
			'message' => 'success',
			'data' => $filter
		], 201);
	}
	
	public function deleteEntity(Request $request)
	{
		$reqType = '\App\\' . $request->type;
		$model = new $reqType;
		
		$cartItems = DB::table($model->getTable())
		->where('Key', $request->key)
		->get()
		->toArray();
		
		DB::table($model->getTable())
		->where('Key', $request->key)
		->delete();

		return response()->json([
			'message' => ucfirst($request->type) . ' ' . $request->key . ' record has been removed',
			'data' => $cartItems
		], 201);
	}
}
