<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Enums\Status;
use App\Traits\HttpResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Contracts\ProductControllerInterface;

class ProductController extends Controller implements ProductControllerInterface
{
    use HttpResponse;

    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();

        $products = $products->map(function ($product) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
            ];
        });

        return response()->json($products);
    }
    public function show(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error("Product Not Found", 404);
        }

        return Product::where('id', $id)->first();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value !== null && !in_array($value, Status::values())) {
                    $fail('O valor de ' . $attribute . ' deve ser um dos seguintes: ' . implode(', ', Status::values()) . '.');
                }
            }],
            'description' => 'nullable',
            'stock_quantity' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid Data', 422, $validator->errors());
        }

        $validatedData = $validator->validated();
        $product = Product::create($validatedData);

        if ($product) {
            return $this->response("Product Created", 200, $product);
        }

        return $this->error("Product Not Created", 400);
    }
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if ($value !== null && !in_array($value, Status::values())) {
                    $fail('O valor de ' . $attribute . ' deve ser um dos seguintes: ' . implode(', ', Status::values()) . '.');
                }
            }],
            'description' => 'nullable',
            'stock_quantity' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid Data', 422, $validator->errors());
        }

        $product = Product::find($id);

        if (!$product) {
            return $this->error("Product Not Found", 404);
        }

        $validatedData = $validator->validated();

        $validatedData['description'] = $validatedData['description'] ?? null;

        $product->update($validatedData);

        return $this->response("Product Updated", 200, $product);
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error("Product Not Found", 404);
        }

        $product->delete();

        return $this->response("Product Deleted", 200);
    }
}
