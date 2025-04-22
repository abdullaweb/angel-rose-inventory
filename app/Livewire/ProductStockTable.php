<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\PurchaseStore;
use Livewire\Component;
use Livewire\WithPagination;

class ProductStockTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;

    protected $updatesQueryString = ['search']; // Optional for URL tracking

    public function updatingSearch()
    {
        $this->resetPage(); // Reset to page 1 on search update
    }

    public function render()
    {
        $allProducts = Product::all();
        $grandTotal = 0;
        $grandQuantity = 0;
        foreach ($allProducts as $product) {
            $purchaseItems = PurchaseStore::where('product_id', $product->id)
                ->where('quantity', '>', 0)
                ->get();

                foreach ($purchaseItems as $key => $value) {
                    $grandQuantity += $value->quantity;
                    $grandTotal += $value->unit_price * $value->quantity;
                }
        }

        return view(
            'livewire.product-stock-table',
            [
                'products' => Product::query()
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->paginate($this->perPage === 'all' ? Product::count() : $this->perPage),
                'grandTotal' => $grandTotal,
                'grandQuantity' => $grandQuantity
            ]
        );
    }

}
