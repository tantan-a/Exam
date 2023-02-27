<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function list(Request $request)
    {
        try {
            $product = Product::all();

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'success' => true,
                    'message' => 'Success!'
                ],
                'data' => $product
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'meta' => [
                    'code' => 500,
                    'success' => false,
                    'message' => 'Internal Server Error !'
                ]
            ]);
        }
    }
}
