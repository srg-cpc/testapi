<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            DB::transaction(function () use ($request, &$category) {
                $category = Category::create($request->all());
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            return response()->json([
                'error' => 'There was an error',
                'code' => '500'
            ])
                ->setStatusCode(500);
        }

        return (new CategoryResource($category))->additional(['code' => 201])
            ->response()->setStatusCode(201);
    }

    /**
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
       return (new CategoryResource($category))->additional(['code' => 200]);
    }

    /**
     * @param Category $category
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Category $category, CategoryRequest $request)
    {
        try {
            DB::transaction(function () use ($category,$request) {
                $category->update($request->all());
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            return response()->json([
                'error' => 'There was an error',
                'code' => 500,
            ]);
        }

        return (new CategoryResource($category))->additional(['code' => 200])
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
                Category::destroy($id);
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

    public function products(Category $category)
    {
        return (new ProductCollection($category->allProducts()))->additional(['code' => 200]);
    }
}
