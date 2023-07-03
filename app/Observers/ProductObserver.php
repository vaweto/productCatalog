<?php

namespace App\Observers;

use App\Jobs\BroadcastProductJob;
use App\Models\ExternalSuppliers;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $external = ExternalSuppliers::where('active', 1)->get();
        $external->each(function ($external) use ($product) {
            dispatch(new BroadcastProductJob($external, $product, $product->getDirty()));
        });
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $external = ExternalSuppliers::where('active', 1)->get();
        $external->each(function ($external) use ($product) {
            dispatch(new BroadcastProductJob($external, $product, $product->getDirty()));
        });
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {

    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
