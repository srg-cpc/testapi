<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @return ProductCollection
     */
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request, &$product) {
                $product = Product::create($request->all());

                $category = Category::find($request->get('category_id'));

                if ($category){
                    $category->products()->attach ($product);
                }
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            return response()->json([
                'error' => 'There was an error',
                'code' => '500'
            ])
                ->setStatusCode(500);
        }

        return ( new ProductResource($product))->additional(['code' => 201])
            ->response()->setStatusCode(201);
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        return (new ProductResource($product))->additional(['code' => 200]);
    }

    /**
     * @param Product $product
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Product $product, ProductRequest $request)
    {
        try {
            DB::transaction(function () use ($product,$request) {
                $product->update($request->all());

                $category = Category::find($request->get('category_id'));

                if ($category){
                    $category->products()->attach ($product);
                }
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            return response()->json([
                'error' => 'There was an error',
                'code' => 500,
            ]);
        }

        return (new ProductResource($product))->additional(['code' => 200])
            ->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Product::destroy($id);
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            return response()->json([
                'error' => 'There was an error',
                'code' => 500,
            ]);
        }

        return response([], 204);
    }
}
