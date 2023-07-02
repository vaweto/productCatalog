<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Tag $tag)
    {
        return new ProductCollection(
          $tag->products()
              ->orderByDesc('id')
              ->paginate(config('controller.pagination_limit'))
        );
    }
}
