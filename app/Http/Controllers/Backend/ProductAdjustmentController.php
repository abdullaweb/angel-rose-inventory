<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SalesProfit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseStore;
use App\Models\PurchaseMeta;
use App\Models\ProductAdjustment;
use App\Models\ProductAdjustmentDetail;
use Illuminate\Support\Facades\Auth;
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
        DB::beginTransaction();
        try {
            $adjustmentStcok = new ProductAdjustment();
            $adjustmentStcok->adjustment_no = $request->stock_no;
            $adjustmentStcok->total_qty = $request->total_quantity;
            $adjustmentStcok->total_amount = $request->estimated_total;
            $adjustmentStcok->date = Carbon::now();
            $adjustmentStcok->created_by = Auth::user()->id;
            $adjustmentStcok->created_at = Carbon::now();
            $adjustmentStcok->save();



            for ($i = 0; $i < count($request->category_id); $i++) {
                $productId = $request->product_id[$i];
                $quantity = $request->quantity[$i];
                $adjustQty = abs($request->quantity[$i]);
                $type = $quantity > 0 ? 'increase' : 'decrease';

                ProductAdjustmentDetail::create([
                    'adjustment_id' => $adjustmentStcok->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $request->unit_price[$i],
                    'type' => $type,
                    'created_at' => Carbon::now(),
                ]);

                if ($type == 'increase') {

                    $purchase = new Purchase();
                    $purchase->purchase_no = $this->UniqueNumberForPurchaseItem();
                    $purchase->date = $adjustmentStcok->date;
                    $purchase->total_amount = $request->estimated_total;
                    $purchase->created_by = Auth::user()->id;
                    $purchase->created_at = Carbon::now();
                    $purchase->type = 'adjustment_' . $adjustmentStcok->id;
                    $purchase->save();

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

                } else {
                    // FIFO decrease (like a sale)
                    $stock = PurchaseStore::where('product_id', $productId)
                        ->where('quantity', '>', 0)
                        ->orderBy('id') // FIFO
                        ->get();

                    $remainingQty = $adjustQty;
                    foreach ($stock as $batch) {
                        if ($remainingQty <= 0) break;

                        $deduct = min($remainingQty, $batch->quantity);

                        // ✅ Save SalesProfit for audit trail
                        SalesProfit::create([
                            'invoice_id' => null, // Not from invoice
                            'adjustment_id' => $adjustmentStcok->id, // NEW: support from stock adjustment
                            'purchase_id' => $batch->id,
                            'product_id' => $productId,
                            'unit_price_purchase' => $batch->unit_price ?? 0,
                            'unit_price_sales' => 0, // no selling price in adjustment
                            'discount_per_unit' => 0,
                            'profit_per_unit' => 0,
                            'profit' => 0,
                            'selling_qty' => $deduct,
                            'date' => now(),
                        ]);

                        $batch->update(['quantity' => $batch->quantity - $deduct]);
                        $remainingQty -= $deduct;
                    }

                    if ($remainingQty > 0) {
                        return back()->with([
                            'message' => 'Not enough stock to decrease',
                            'alert-type' => 'error',
                        ]);
                    }
                }
            }


            DB::commit();
            $notification = [
                'message' => 'Product Adjustment Added Successfully!',
                'alert_type' => 'info',
            ];
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
        $adjustment = ProductAdjustment::get();
        return view('admin.adjustment.adjustment_stock.adjustment_stock_list', compact('adjustment'));
    }

    public function ProductAdjustmentEdit($id)
    {
        $categories = Category::OrderBy('name', 'asc')->get();
        $products = Product::OrderBy('name', 'asc')->get();
        $adjustment = ProductAdjustment::with('details')->findOrFail($id);
        return view('admin.adjustment.adjustment_stock.edit_adjustment_stock', compact('adjustment', 'categories', 'products'));
    }


    public function ProductAdjustmentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $stock_id = $request->id;
            $adjustment = ProductAdjustment::with('details')->findOrFail($stock_id);
            $this->restoreAdjustment($adjustment);

            $adjustment->total_qty = $request->total_quantity;
            $adjustment->total_amount = $request->estimated_total;
            $adjustment->updated_at = Carbon::now();
            $adjustment->save();



            for ($i = 0; $i < count($request->category_id); $i++) {
                $productId = $request->product_id[$i];
                $categoryId = $request->category_id[$i];
                $quantity = $request->quantity[$i];
                $adjustQty = abs($request->quantity[$i]);
                $type = $quantity > 0 ? 'increase' : 'decrease';

                ProductAdjustmentDetail::create([
                    'adjustment_id' => $adjustment->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $request->unit_price[$i],
                    'type' => $type,
                    'created_at' => Carbon::now(),
                ]);

                if ($type == 'increase') {
                    $purchase = Purchase::where('type', 'adjustment_' . $adjustment->id)->first();
                    if ($purchase) {
                        $purchase->total_amount = $request->estimated_total;
                        $purchase->save();
                    } else {
                        $purchase = new Purchase();
                        $purchase->purchase_no = $this->UniqueNumberForPurchaseItem();
                        $purchase->date = $request->date;
                        $purchase->total_amount = $request->estimated_total;
                        $purchase->created_by = Auth::user()->id;
                        $purchase->created_at = Carbon::now();
                        $purchase->type = 'adjustment_' . $adjustment->id;
                        $purchase->save();
                    }

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
                } else {
                    // FIFO decrease (like a sale)
                    $stock = PurchaseStore::where('product_id', $productId)
                        ->where('quantity', '>', 0)
                        ->orderBy('id') // FIFO
                        ->get();

                    $remainingQty = $adjustQty;
                    foreach ($stock as $batch) {
                        if ($remainingQty <= 0) break;

                        $deduct = min($remainingQty, $batch->quantity);

                        // ✅ Save SalesProfit for audit trail
                        SalesProfit::create([
                            'invoice_id' => null, // Not from invoice
                            'adjustment_id' => $adjustment->id, // NEW: support from stock adjustment
                            'purchase_id' => $batch->id,
                            'product_id' => $productId,
                            'unit_price_purchase' => $batch->unit_price ?? 0,
                            'unit_price_sales' => 0, // no selling price in adjustment
                            'discount_per_unit' => 0,
                            'profit_per_unit' => 0,
                            'profit' => 0,
                            'selling_qty' => $deduct,
                            'date' => now(),
                        ]);

                        $batch->update(['quantity' => $batch->quantity - $deduct]);
                        $remainingQty -= $deduct;
                    }

                    if ($remainingQty > 0) {
                        return back()->with([
                            'message' => 'Not enough stock to decrease',
                            'alert-type' => 'error',
                        ]);
                    }
                }
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

    private function restoreAdjustment($adjustment)
    {
        foreach ($adjustment->details as $detail) {
            $productId = $detail->product_id;

            if ($detail->type === 'increase') {
                // Remove related PurchaseStore entries
                $purchase = Purchase::where('type', 'adjustment_' . $adjustment->id)->first();
                PurchaseStore::where('purchase_id', $purchase->id)->where('product_id', $productId)->delete();
                PurchaseMeta::where('purchase_id', $purchase->id)->where('product_id', $productId)->delete();
            } else {
                // Restore quantities to FIFO-ed batches
                $history = SalesProfit::where('adjustment_id', $adjustment->id)
                    ->where('product_id', $productId)
                    ->get();

                foreach ($history as $record) {
                    $purchaseStore = PurchaseStore::find($record->purchase_id);
                    if ($purchaseStore) {
                        $purchaseStore->update(['quantity' => $purchaseStore->quantity + $record->selling_qty]);
                    }
                }

                // Optionally clean up logs
                SalesProfit::where('adjustment_id', $adjustment->id)
                    ->where('product_id', $productId)
                    ->delete();
            }
            $detail->delete();
        }
    }

    public function ProductAdjustmentDelete($id)
    {
        DB::beginTransaction();
        try {
            $adjustment = ProductAdjustment::with('details')->findOrFail($id);

            foreach ($adjustment->details as $detail) {
                $productId = $detail->product_id;

                if ($detail->type === 'increase') {
                    // Remove related PurchaseStore entries
                    $purchase = Purchase::where('type', 'adjustment_' . $adjustment->id)->first();
                    PurchaseStore::where('purchase_id', $purchase->id)->where('product_id', $productId)->delete();
                    PurchaseMeta::where('purchase_id', $purchase->id)->where('product_id', $productId)->delete();
                } else {
                    // Restore quantities to FIFO-ed batches
                    $history = SalesProfit::where('adjustment_id', $adjustment->id)
                        ->where('product_id', $productId)
                        ->get();

                    foreach ($history as $record) {
                        $purchaseStore = PurchaseStore::find($record->purchase_id);
                        if ($purchaseStore) {
                            $purchaseStore->update(['quantity' => $purchaseStore->quantity + $record->selling_qty]);
                        }
                    }

                    // Optionally clean up logs
                    SalesProfit::where('adjustment_id', $adjustment->id)
                        ->where('product_id', $productId)
                        ->delete();
                }
                $detail->delete();
            }

            $purchase = Purchase::where('type', 'adjustment_' . $id)->first();
            if ($purchase) {
                $purchase->delete();
            }
            $adjustment->delete();

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
        $adjustment = ProductAdjustment::with('details')->findOrFail($id);
        return view('admin.adjustment.adjustment_stock.view_adjustment_stock', compact('adjustment'));
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
