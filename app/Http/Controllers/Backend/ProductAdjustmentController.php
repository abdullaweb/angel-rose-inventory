<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseStore;
use App\Models\PurchaseMeta;
use App\Models\ProductAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAdjustmentController extends Controller
{
    public function ProductAdjustmentAdd()
    {
        $categories = Category::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $stock_no = $this->UniqueNumber();
        return view('admin.adjustment.adjustment_stock.add_adjustment_stock', compact('stock_no', 'categories', 'products'));
    }

    public function UniqueNumberForPurchaseItem()
    {
        $purchase = Purchase::latest()->first();
        if ($purchase) {
            $name = $purchase->purchase_no;
            $number = explode('_', $name);
            $purchase_no = 'PS_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $purchase_no = 'PS_000001';
        }
        return $purchase_no;
    }

    public function ProductAdjustmentStore(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $purchaseStock = new ProductAdjustment();
            $purchaseStock->adjustment_no = $request->stock_no;
            $purchaseStock->total_qty = $request->total_quantity;
            $purchaseStock->total_amount = $request->estimated_total;
            $purchaseStock->date = Carbon::now();
            $purchaseStock->created_by = Auth::user()->id;
            $purchaseStock->created_at = Carbon::now();
            $purchaseStock->save();

            $purchase = new Purchase();
            $purchase->purchase_no = $this->UniqueNumberForPurchaseItem();
            $purchase->date = $request->date;
            $purchase->total_amount = $request->estimated_total;          
            $purchase->created_by = Auth::user()->id;
            $purchase->created_at = Carbon::now();
            $purchase->type = 'adjustment_' . $purchaseStock->id;
            $purchase->save();

            for ($i = 0; $i < count($request->category_id); $i++) {
                $purchaseStores = new PurchaseStore();
                $purchaseStores->purchase_id = $purchase->id;
                $purchaseStores->product_id = $request->product_id[$i];
                $purchaseStores->quantity = $request->quantity[$i];
                $purchaseStores->unit_price = $request->unit_price[$i];
                $purchaseStores->created_at = Carbon::now();
                $purchaseStores->save();

                $purchaseMeta = new PurchaseMeta();
                $purchaseMeta->purchase_id = $purchase->id;
                $purchaseMeta->category_id = $request->category_id[$i];
                $purchaseMeta->product_id = $request->product_id[$i];
                $purchaseMeta->quantity = $request->quantity[$i];
                $purchaseMeta->unit_price = $request->unit_price[$i];
                $purchaseMeta->total = $request->unit_price[$i] * $request->quantity[$i];
                $purchaseMeta->created_at = Carbon::now();
                $purchaseMeta->save();
            }
            

            DB::commit();
            $notification = array(
                'message' => 'Product Adjustment Added Successfully!',
                'alert_type' => 'info',
            );
            return redirect()->route('product.adjustment.all')->with($notification);
        } catch (\Throwable $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();

            // Log the error message if needed
            Log::error('Error creating adjustment: ' . $e->getMessage());

            // Return an error notification
            $notification = [
                'message' => 'Failed to creating adjustment. Please try again later.',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function ProductAdjustmentAll()
    {
        $purchaseStock = ProductAdjustment::get();
        return view('admin.adjustment.adjustment_stock.adjustment_stock_list', compact('purchaseStock'));
    }

    public function ProductAdjustmentEdit($id)
    {
        $categories = Category::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $stockInfo = ProductAdjustment::findOrFail($id);
        $purchase = Purchase::where('type', 'adjustment_' . $id)->first();
        $purchaseStore =  PurchaseStore::where('purchase_id', $purchase->id)->get();
        return view('admin.adjustment.adjustment_stock.edit_adjustment_stock', compact('purchaseStore', 'categories', 'products', 'stockInfo'));
    }


    public function ProductAdjustmentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $date = date('d-m-Y');
            $stock_id = $request->id;
            $purchaseStock = ProductAdjustment::findOrFail($stock_id);
            $purchase = Purchase::where('type', 'adjustment_' . $stock_id)->first();
            $this->restorePurchase($purchaseStock, $purchase);

            $purchaseStock->total_qty = $request->total_quantity;
            $purchaseStock->total_amount = $request->estimated_total;
            $purchaseStock->date = date('Y-m-d', strtotime($date));
            $purchaseStock->save();

            $purchase->date = date('Y-m-d', strtotime($date));
            $purchase->save();

            for ($i = 0; $i < count($request->category_id); $i++) {
                $purchaseStores = new PurchaseStore();
                $purchaseStores->purchase_id = $purchase->id;
                $purchaseStores->product_id = $request->product_id[$i];
                $purchaseStores->quantity = $request->quantity[$i];
                $purchaseStores->unit_price = $request->unit_price[$i];
                $purchaseStores->created_at = Carbon::now();
                $purchaseStores->save();

                $purchaseMeta = new PurchaseMeta();
                $purchaseMeta->purchase_id = $purchase->id;
                $purchaseMeta->category_id = $request->category_id[$i];
                $purchaseMeta->product_id = $request->product_id[$i];
                $purchaseMeta->quantity = $request->quantity[$i];
                $purchaseMeta->unit_price = $request->unit_price[$i];
                $purchaseMeta->total = $request->unit_price[$i] * $request->quantity[$i];
                $purchaseMeta->created_at = Carbon::now();
                $purchaseMeta->save();
            }


            DB::commit();
            $notification = array(
                'message' => 'Stock Updated Successfully!',
                'alert_type' => 'info',
            );
            return redirect()->route('product.adjustment.all')->with($notification);
        } catch (\Throwable $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();

            // Log the error message if needed
            Log::error('Error updating adjustment: ' . $e->getMessage());

            // Return an error notification
            $notification = [
                'message' => 'Failed to update adjustment. Please try again later.',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
    }

    private function restorePurchase($purchaseStock, $purchase)
    {
        if ($purchase) {
            PurchaseStore::where('purchase_id',  $purchase->id)->delete();
            PurchaseMeta::where('purchase_id',  $purchase->id)->delete();
        }
    }
    public function ProductAdjustmentDelete($id)
    {
        DB::beginTransaction();
        try {
            ProductAdjustment::findOrFail($id)->delete();
            $purchase = Purchase::where('type', 'adjustment_' . $id)->first();
            if ($purchase) {
                PurchaseStore::where('purchase_id',  $purchase->id)->delete();
                PurchaseMeta::where('purchase_id',  $purchase->id)->delete();
            }
            $purchase->delete();

            DB::commit();
            $notification = array(
                'message' => 'Adjustment Deleted Successfully!',
                'alert_type' => 'info',
            );
            return redirect()->route('product.adjustment.all')->with($notification);
        } catch (\Throwable $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();

            // Log the error message if needed
            Log::error('Error deleting adjustment: ' . $e->getMessage());

            // Return an error notification
            $notification = [
                'message' => 'Failed to delete adjustment. Please try again later.',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function ProductAdjustmentView($id)
    {
        $stockInfo = ProductAdjustment::findOrFail($id);
        $purchase = Purchase::where('type', 'adjustment_' . $id)->first();
        $purchaseStore =  PurchaseStore::where('purchase_id', $purchase->id)->get();
        return view('admin.adjustment.adjustment_stock.view_adjustment_stock', compact('purchaseStore', 'stockInfo'));
    }

    public function UniqueNumber()
    {
        $purchaseStock = ProductAdjustment::latest()->first();
        // dd($purchaseStock);
        if ($purchaseStock) {
            $name = $purchaseStock->adjustment_no;
            $number = explode('_', $name);
            $stock_no = 'ADJ_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $stock_no = 'ADJ_000001';
        }
        return $stock_no;
    }



    public function GetProduct($id)
    {
        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }

}
