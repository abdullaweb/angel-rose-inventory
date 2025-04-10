<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\SalesProfit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function InvoiceAll()
    {
        $allInvoice = Invoice::where('status', 1)->where('return_status', 0)->orderBy('id', 'desc')->latest()->paginate(10);

        $duplicates = Invoice::select('invoice_no', DB::raw('COUNT(*) as count'))
            ->groupBy('invoice_no')
            ->whereYear('date', '2025')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        return view('admin.invoice.invoice_all', compact('allInvoice'));
    }

    public function InvoiceAdd()
    {
        $customers = Customer::all();
        $categories = Category::all();
        $products = Product::all();
        $allBank = Bank::OrderBy('name', 'asc')->get();
        $invoice_no = $this->UniqueNumber();
        return view('admin.invoice.invoice_add', compact('invoice_no', 'customers', 'categories', 'products', 'allBank'));
    }


    public function InvoiceStore(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate paid amount
            if ($request->paid_amount > $request->estimated_total) {
                return redirect()->back()->with([
                    'message' => 'Sorry, Paid amount exceeds the total amount',
                    'alert-type' => 'error',
                ]);
            }

            // Handle customer creation or existing selection
            $customer_id = $request->customer_id;
            if ($customer_id == '0') {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'status' => $request->customer_type,
                    'created_at' => now(),
                ]);
                $customer_id = $customer->id;
            }

            // Create Invoice
            $invoice = Invoice::create([
                'invoice_no'  => $request->invoice_no,
                'date'        => $request->date,
                'customer_id' => $customer_id,
                'status'      => '0',
                'created_by'  => Auth::id(),
                'created_at'  => now(),
            ]);

            // Process Invoice Details
            foreach ($request->category_id as $key => $category_id) {
                $product_id = $request->product_id[$key];
                $selling_qty = $request->selling_qty[$key];
                $unit_price = $request->unit_price[$key];
                $selling_price = $request->selling_price[$key];

                // Calculate Discount
                $discount_per_qty = 0;
                $discount_amount = 0;

                if (!empty($request->discount_type) && $request->discount_rate > 0) {
                    if ($request->discount_type === 'percentage') {
                        $discount_per_qty = round(($request->discount_rate / 100) * $unit_price, 2);
                    } elseif ($request->total_quantity > 0) { // Avoid division by zero
                        $discount_per_qty = round($request->discount_rate / $request->total_quantity, 2);
                    }

                    $discount_amount = $discount_per_qty * $selling_qty;
                }


                // Create Invoice Detail
                InvoiceDetail::create([
                    'date'         => $request->date,
                    'invoice_id'   => $invoice->id,
                    'category_id'  => $category_id,
                    'product_id'   => $product_id,
                    'selling_qty'  => $selling_qty,
                    'unit_price'   => $unit_price,
                    'selling_price' => $selling_price,
                ]);

                // Check stock availability
                $productStock = PurchaseStore::where('product_id', $product_id)->where('quantity', '!=', 0)->get();
                if ($productStock->sum('quantity') < $selling_qty) {
                    return redirect()->back()->with([
                        'message' => 'Sorry, Stock is not available',
                        'alert-type' => 'error',
                    ]);
                }

                // FIFO Stock Management & Profit Calculation
                $remaining_qty = $selling_qty;
                foreach ($productStock as $purchase) {
                    $sales_qty = min($remaining_qty, $purchase->quantity);
                    SalesProfit::create([
                        'invoice_id'         => $invoice->id,
                        'purchase_id'        => $purchase->id,
                        'product_id'         => $product_id,
                        'unit_price_purchase' => $purchase->unit_price,
                        'discount_per_unit'  => $discount_per_qty,
                        'unit_price_sales'   => $unit_price,
                        'profit_per_unit'    => $unit_price - $purchase->unit_price - $discount_per_qty,
                        'selling_qty'        => $sales_qty,
                        'profit'             => ($unit_price - $purchase->unit_price - $discount_per_qty) * $sales_qty,
                        'date'               => $request->date,
                        'created_at'         => now(),
                    ]);

                    // Update stock quantity
                    $purchase->update(['quantity' => $purchase->quantity - $sales_qty]);
                    $remaining_qty -= $sales_qty;
                    if ($remaining_qty <= 0) break;
                }
            }

            // Handle Payment Source
            $bank_name = null;
            $note = null;
            if ($request->paid_source == 'bank') {
                $bank_name = $request->bank_name;
                $note = $request->check_number;
            } elseif ($request->paid_source == 'mobile-banking') {
                $bank_name = $request->mobile_bank;
                $note = $request->transaction_number;
            } elseif ($request->paid_source == 'online-banking') {
                $note = $request->note;
            }

            // Handle Payment & Account Details
            $latestAccount = AccountDetail::where('customer_id', $invoice->customer_id)->latest('id')->first();
            // $balance = $latestAccount ? $latestAccount->balance + $request->estimated_total : $request->estimated_total;
            $previous_balance = $latestAccount ? $latestAccount->balance : 0;

            $payment_data = [
                'invoice_id'      => $invoice->id,
                'customer_id'     => $invoice->customer_id,
                'paid_status'     => $request->paid_status,
                'discount_amount' => $discount_amount,
                'discount_type'   => $request->discount_type,
                'total_amount'    => $request->estimated_total,
            ];

            $payment_details_data = [
                'invoice_id'    => $invoice->id,
                'date'          => $request->date,
                'paid_status'   => $request->paid_status,
                'paid_source'   => $request->paid_source,
                'bank_name'     => $bank_name,
                'note'          => $note,
            ];

            $account_details_data = [
                'invoice_id'    => $invoice->id,
                'customer_id'   => $invoice->customer_id,
                'total_amount'  => $request->estimated_total,
                'status'        => '1',
                'date'          => $request->date,
                'paid_status'   => $request->paid_status,
                'paid_source'   => $request->paid_source,
                'bank_name'     => $bank_name,
                'note'          => $note,
                // 'balance'       => $balance,
            ];

            // Payment Status Handling
            if ($request->paid_status == 'full_paid') {
                $payment_data['paid_amount'] = $request->estimated_total;
                $payment_data['due_amount'] = 0;
                $payment_details_data['current_paid_amount'] = $request->estimated_total;

                $account_details_data['paid_amount'] = $request->estimated_total;
                $account_details_data['due_amount'] = 0;
                $account_details_data['balance'] = $previous_balance;

            } elseif ($request->paid_status == 'full_due') {
                $payment_data['paid_amount'] = 0;
                $payment_data['due_amount'] = $request->estimated_total;
                $payment_details_data['current_paid_amount'] = 0;

                $account_details_data['paid_amount'] = 0;
                $account_details_data['due_amount'] = $request->estimated_total;
                $account_details_data['balance'] = $previous_balance + $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment_data['paid_amount'] = $request->paid_amount;
                $payment_data['due_amount'] = $request->estimated_total - $request->paid_amount;
                $payment_details_data['current_paid_amount'] = $request->paid_amount;

                $account_details_data['paid_amount'] = $request->paid_amount;
                $account_details_data['due_amount'] = $request->estimated_total - $request->paid_amount;
                $account_details_data['balance'] = $previous_balance + $request->estimated_total - $request->paid_amount;
            }

            Payment::create($payment_data);
            PaymentDetail::create($payment_details_data);
            AccountDetail::create($account_details_data);

            // Finalize Invoice
            $invoice->update(['status' => '1']);

            DB::commit();

            return redirect()->route('invoice.all')->with([
                'message' => 'Invoice Created Successfully',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error Creating Invoice - User: " . Auth::id() . " - Error: " . $th->getMessage());
            return redirect()->route('invoice.add')->with([
                'message' => 'Sorry, Something went wrong',
                'alert-type' => 'error',
            ]);
        }
    }


    public function InvoiceEdit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $customers = Customer::OrderBy('name', 'asc')->get();
        $categories = Category::all();
        $products = Product::all();
        $allBank = Bank::OrderBy('name', 'asc')->get();
        return view('admin.invoice.invoice_edit', compact('invoice', 'customers', 'categories', 'products', 'allBank'));
    }
    public function InvoiceUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $invoice_id = $request->id;
            $invoice = Invoice::findOrFail($invoice_id);

            if ($request->paid_amount > $request->estimated_total) {
                return redirect()->back()->with([
                    'message' => 'Sorry, Paid amount is maximum the total amount',
                    'alert-type' => 'error',
                ]);
            }

            // Restore stock and sales before updating invoice details
            $this->restoreStockAndSales($invoice_id, $request->customer_id);

            // Update invoice
            $invoice->update([
                'invoice_no'   => $request->invoice_no,
                'date'         => $request->date,
                'customer_id'  => $request->customer_id,
                'updated_by'   => Auth::user()->id,
            ]);

            /** ============ Continue with invoice update logic here ============ */
            // Process Invoice Details
            foreach ($request->category_id as $key => $category_id) {
                $product_id = $request->product_id[$key];
                $selling_qty = $request->selling_qty[$key];
                $unit_price = $request->unit_price[$key];
                $selling_price = $request->selling_price[$key];

                // Calculate Discount
                $discount_per_qty = 0;
                $discount_amount = 0;

                if (!empty($request->discount_type) && $request->discount_rate > 0) {
                    if ($request->discount_type === 'percentage') {
                        $discount_per_qty = round(($request->discount_rate / 100) * $unit_price, 2);
                    } elseif ($request->total_quantity > 0) { // Avoid division by zero
                        $discount_per_qty = round($request->discount_rate / $request->total_quantity, 2);
                    }

                    $discount_amount = $discount_per_qty * $selling_qty;
                }


                // Create Invoice Detail
                InvoiceDetail::create([
                    'date'         => $request->date,
                    'invoice_id'   => $invoice->id,
                    'category_id'  => $category_id,
                    'product_id'   => $product_id,
                    'selling_qty'  => $selling_qty,
                    'unit_price'   => $unit_price,
                    'selling_price' => $selling_price,
                ]);

                // Check stock availability
                $productStock = PurchaseStore::where('product_id', $product_id)->where('quantity', '>', 0)->get();
                if ($productStock->sum('quantity') < $selling_qty) {
                    return redirect()->back()->with([
                        'message' => 'Sorry, Stock is not available',
                        'alert-type' => 'error',
                    ]);
                }

                // FIFO Stock Management & Profit Calculation
                $remaining_qty = $selling_qty;
                foreach ($productStock as $purchase) {
                    $sales_qty = min($remaining_qty, $purchase->quantity);
                    SalesProfit::create([
                        'invoice_id'         => $invoice->id,
                        'purchase_id'        => $purchase->id,
                        'product_id'         => $product_id,
                        'unit_price_purchase' => $purchase->unit_price,
                        'discount_per_unit'  => $discount_per_qty,
                        'unit_price_sales'   => $unit_price,
                        'profit_per_unit'    => $unit_price - $purchase->unit_price - $discount_per_qty,
                        'selling_qty'        => $sales_qty,
                        'profit'             => ($unit_price - $purchase->unit_price - $discount_per_qty) * $sales_qty,
                        'date'               => $request->date,
                        'created_at'         => now(),
                    ]);

                    // Update stock quantity
                    $purchase->update(['quantity' => $purchase->quantity - $sales_qty]);
                    $remaining_qty -= $sales_qty;
                    if ($remaining_qty <= 0) break;
                }
            }

            // Handle Payment Source
            $bank_name = null;
            $note = null;
            if ($request->paid_source == 'bank') {
                $bank_name = $request->bank_name;
                $note = $request->check_number;
            } elseif ($request->paid_source == 'mobile-banking') {
                $bank_name = $request->mobile_bank;
                $note = $request->transaction_number;
            } elseif ($request->paid_source == 'online-banking') {
                $note = $request->note;
            }

            // Handle Payment & Account Details
            $latestAccount = AccountDetail::where('invoice_id', $invoice->id)->first();
            $previousBalance = $latestAccount->balance - $latestAccount->due_amount;

            // dd($latestAccount->balance, $previousBalance);

            $payment_data = [
                'invoice_id'      => $invoice->id,
                'customer_id'     => $invoice->customer_id,
                'paid_status'     => $request->paid_status,
                'discount_amount' => $discount_amount,
                'discount_type'   => $request->discount_type,
                'total_amount'    => $request->estimated_total,
            ];

            $payment_details_data = [
                'invoice_id'    => $invoice->id,
                'date'          => $request->date,
                'paid_status'   => $request->paid_status,
                'paid_source'   => $request->paid_source,
                'bank_name'     => $bank_name,
                'note'          => $note,
            ];

            $account_details_data = [
                'invoice_id'    => $invoice->id,
                'customer_id'   => $invoice->customer_id,
                'total_amount'  => $request->estimated_total,
                'status'        => '1',
                'date'          => $request->date,
                'paid_status'   => $request->paid_status,
                'paid_source'   => $request->paid_source,
                'bank_name'     => $bank_name,
                'note'          => $note,
            ];

            // Payment Status Handling
            if ($request->paid_status == 'full_paid') {
                $payment_data['paid_amount'] = $request->estimated_total;
                $payment_data['due_amount'] = 0;
                $payment_details_data['current_paid_amount'] = $request->estimated_total;

                $account_details_data['paid_amount'] = $request->estimated_total;
                $account_details_data['due_amount'] = 0;
                $account_details_data['balance'] = $previousBalance;

            } elseif ($request->paid_status == 'full_due') {
                $payment_data['paid_amount'] = 0;
                $payment_data['due_amount'] = $request->estimated_total;
                $payment_details_data['current_paid_amount'] = 0;

                $account_details_data['paid_amount'] = 0;
                $account_details_data['due_amount'] = $request->estimated_total;
                $account_details_data['balance'] = $previousBalance + $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment_data['paid_amount'] = $request->paid_amount;
                $payment_data['due_amount'] = $request->estimated_total - $request->paid_amount;
                $payment_details_data['current_paid_amount'] = $request->paid_amount;

                $account_details_data['paid_amount'] = $request->paid_amount;
                $account_details_data['due_amount'] = $request->estimated_total - $request->paid_amount;
                $account_details_data['balance'] = $previousBalance + $request->estimated_total - $request->paid_amount;
            }

            Payment::create($payment_data);
            PaymentDetail::create($payment_details_data);
            $latestAccount = AccountDetail::where('invoice_id', $invoice->id)->latest('id')->first();
            if ($latestAccount) {
                $latestAccount->update($account_details_data);
            } else {
                AccountDetail::create($account_details_data);
            }

            // Finalize Invoice
            $invoice->update(['status' => '1']);

            //account details balance update
            $this->resetAccountBalance($invoice);

            DB::commit();

            return redirect()->route('invoice.all')->with([
                'message' => 'Invoice Updated Successfully!',
                'alert_type' => 'success',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error Updating Invoice: ' . $th->getMessage() . ' Line: ' . $th->getLine());
            return redirect()->back()->with([
                'message' => 'Sorry, Something went wrong',
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * Restore stock and delete sales information
     */
    private function restoreStockAndSales($invoice_id, $customer_id)
    {
        $salesProducts = SalesProfit::where('invoice_id', $invoice_id)->get();

        foreach ($salesProducts as $product) {
            $purchaseStoreInfo = PurchaseStore::where('id', $product->purchase_id)
                ->where('product_id', $product->product_id)
                ->first();

            if ($purchaseStoreInfo) {
                $purchaseStoreInfo->increment('quantity', $product->selling_qty);
            }
            $product->delete();
        }



        // Delete related payment and account details
        InvoiceDetail::where('invoice_id', $invoice_id)->delete();
        Payment::where('invoice_id', $invoice_id)->delete();
        PaymentDetail::where('invoice_id', $invoice_id)->delete();
    }

    //account details balance update
    private function resetAccountBalance($invoice)
    {
        $accountDetail = AccountDetail::where('customer_id', $invoice->customer_id)
            ->where('invoice_id', $invoice->id)
            ->first();

        if ($accountDetail) {
            $nextAccountDetails = AccountDetail::where('customer_id', $invoice->customer_id)
                ->where('id', '>', $accountDetail->id)
                ->orderBy('id')
                ->get();

            $previous_balance = $accountDetail->balance;

            foreach ($nextAccountDetails as $next) {
                if($next->total_amount > 0){
                    $next->balance = $next->total_amount - $next->paid_amount + $previous_balance;
                    $next->due_amount = $next->balance;
                }elseif($next->total_amount == 0){
                    $next->balance = $previous_balance - $next->paid_amount;
                    $next->due_amount = $next->balance;
                }
                $next->save();
                $previous_balance = $next->balance;
            }

        }

    }


    public function InvoiceView($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('admin.invoice.invoice_view', compact('invoice'));
    }


    public function InvoicePrint($id)
    {
        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        $invoiceDetails = InvoiceDetail::where('invoice_id', $invoice->id)->get();
        // dd($invoiceDetails);
        return view('admin.pdf.invoice_pdf', compact('invoice', 'invoiceDetails'));
    }

    public function InvoiceDelete($id)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);

            // Delete related invoice details, payments, and payment details in batch
            InvoiceDetail::where('invoice_id', $invoice->id)->delete();
            Payment::where('invoice_id', $invoice->id)->delete();
            PaymentDetail::where('invoice_id', $invoice->id)->delete();

            // Fetch and delete AccountDetails while adjusting future balances
            $accountDetails = AccountDetail::where('invoice_id', $invoice->id)->get();
            foreach ($accountDetails as $account) {
                AccountDetail::where('customer_id', $invoice->customer_id)
                    ->where('id', '>', $account->id)
                    ->decrement('balance', $account->balance);

                $account->delete();
            }

            // Restore stock quantities and delete sales profit entries
            $salesProducts = SalesProfit::where('invoice_id', $invoice->id)->get();
            foreach ($salesProducts as $sale) {
                PurchaseStore::where('id', $sale->purchase_id)
                    ->increment('quantity', $sale->selling_qty);

                $sale->delete();
            }

            // Delete invoice
            $invoice->delete();

            DB::commit();

            return redirect()->back()->with([
                'message' => 'Invoice Deleted Successfully',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error Deleting Invoice: {$th->getMessage()} in {$th->getFile()} at line {$th->getLine()}");

            return redirect()->back()->with([
                'message' => 'Sorry, Something went wrong',
                'alert-type' => 'error',
            ]);
        }
    }


    public function UniqueNumber()
    {
        $invoice = Invoice::orderByDesc('id')->first();
        if ($invoice) {
            $name = $invoice->invoice_no;
            $number = explode('_', $name);
            $invoice_no = 'INV_' . str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $invoice_no = 'INV_000001';
        }
        return $invoice_no;
    }

    public function GetProduct($id)
    {
        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }

    public function DynamicQuery(Request $request)
    {
        $duplicates = Invoice::select('invoice_no', DB::raw('COUNT(*) as count'))
            ->groupBy('invoice_no')
            ->whereYear('date', '2025')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $invoice = Invoice::where('invoice_no', $duplicate->invoice_no)->first();

            $salesProducts = SalesProfit::where('invoice_id',  $invoice->id)->get();
            foreach ($salesProducts as $sale) {
                $purchaseStoreInfo = PurchaseStore::where('purchase_id', $sale->purchase_id)->first();
                if ($purchaseStoreInfo) {
                    $purchaseStoreInfo->quantity = $purchaseStoreInfo->quantity + $sale->selling_qty;
                    $purchaseStoreInfo->update();
                }
                $sale->delete();
            }

            $payment = Payment::where('invoice_id', $invoice->id)->delete();
            $payment_details = PaymentDetail::where('invoice_id', $invoice->id)->delete();
            $account_details = AccountDetail::where('invoice_id', $invoice->id)->delete();
            $invoice->delete();
        }
    }
}
