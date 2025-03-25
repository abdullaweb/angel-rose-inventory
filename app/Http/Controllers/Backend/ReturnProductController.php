<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\SalesProfit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();
        try{
            $invoice_id = $request->invoice_id;
            $returnProduct = SalesProfit::where('invoice_id', $invoice_id)->get();
            foreach ($returnProduct as $item) {
                $purchaseInfo = PurchaseStore::where('id', $item->purchase_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                if($purchaseInfo){
                    $purchaseInfo->quantity = $purchaseInfo->quantity + $item->selling_qty;
                    $purchaseInfo->update();
                }
            }
            $invoice = Invoice::findOrFail($invoice_id);
            $invoice->return_status = '1';
            $invoice->return_reason = $request->return_reason;
            $invoice->update();

            DB::commit();
    
            $notification = array(
                'message' => 'Product Return Added To Stock',
                'alert-type' => 'success'
            );
    
            return redirect()->route('all.return.product')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Return Storing error' . $e->getMessage() . ' Line: ' . $e->getLine());
            $notification = array(
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
       
    }
}
