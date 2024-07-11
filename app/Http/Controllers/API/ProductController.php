<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse {
        $products = Product::all();
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    public function store(Request $request): JsonResponse {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);
        return $this->sendResponse($product, 'Product created successfully.');
    }


    


    public function show(int $id): JsonResponse {
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }


    public function update(Request $request, Product $product): JsonResponse {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->update($input);
        return $this->sendResponse($product, 'Product updated successfully.');
    }


    public function destroy(Product $product): JsonResponse {
        $product->delete();
        return $this->sendResponse([], 'Product deleted successfully.');
    }

    protected function sendResponse($result, $message): JsonResponse {
        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $message,
        ], 200);
    }


    protected function sendError($error, $errorMessages = [], $code = 404): JsonResponse {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
