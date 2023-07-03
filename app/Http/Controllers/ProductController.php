<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductWithLinksResource;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new ProductCollection(
            Product::query()
                ->filter($request)
                ->with('category')
                ->orderByDesc('id')
                ->paginate(config('controller.pagination_limit'))
                ->withQueryString()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = DB::transaction(
                callback: fn (): Model => Product::query()->create(
                    attributes: $request->validated(),
                ),
            );

            if ($request->validated('tags')) {
                $tags = Tag::findMany($request->validated('tags'));
                $product->tags()->saveMany($tags);
            }

            return response()->json($product->toArray(), 201);

        } catch (\Exception $exception) {
            return abort(500, $exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductWithLinksResource(
            $product->load([
                'category:id,name',
                'tags:id,name',
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::transaction(
                callback: fn () => $product->update(
                    attributes: $request->validated(),
                ),
            );

            if ($request->validated('tags')) {
                $tags = Tag::findMany($request->validated('tags'));
                $product->tags()->sync($tags);
            }

            return response()->json($product->fresh()->toArray());

        } catch (\Exception $exception) {
            return abort(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json('deleted');
        } catch (\Exception $exception) {
            return abort(500, $exception->getMessage());
        }

    }
}
