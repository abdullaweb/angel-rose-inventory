<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\SalesProfit;

class ReturnProductController extends Controller
{
    public function AllReturnProduct()
    {
        $returnProduct = Invoice::where('status', '1')->where('return_status', '1')->latest()->get();
        return view('admin.return_product.all_return_product', compact('returnProduct'));
    }

    public function AddReturnProduct()
    {
        $allInvoice = Invoice::where('status', '1')
            ->where('return_status', '0')
            ->get();
        return view('admin.return_product.add_return_product', compact('allInvoice'));
    }

    public function StoreReturnProduct(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $returnProduct = SalesProfit::where('invoice_id', $invoice_id)->get();
        foreach ($returnProduct as $item) {
            $purchaseInfo = PurchaseStore::where('purchase_id', $item->purchase_id)
                ->where('product_id', $item->product_id)
                ->first();
            $purchaseInfo->quantity = $purchaseInfo->quantity + $item->selling_qty;
            $purchaseInfo->update();

            $product = Product::where('id', $item->product_id)->first();
            $product->quantity = $product->quantity + $item->selling_qty;
            $product->update();
        }
        $invoice = Invoice::findOrFail($invoice_id);
        $invoice->return_status = '1';
        $invoice->return_reason = $request->return_reason;
        $invoice->update();

        $notification = array(
            'message' => 'Product Return Added To Stock',
            'alert-type' => 'success'
        );

        return redirect()->route('all.return.product')->with($notification);
    }
}
