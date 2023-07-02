<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TagResource::collection(
            Tag::query()->orderByDesc('id')
                ->paginate(config('controller.pagination_limit'))
        );
    }
}
