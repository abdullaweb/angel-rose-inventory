<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\BankDetail;
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

class InvoiceController extends Controller
{
    public function InvoiceAll()
    {
        // $allInvoice = Invoice::where('status', '1')->where('return_status', '0')->latest()->get();
        $allInvoice = Invoice::where('status', '1')->where('return_status', '0')->latest()->take(10)->get();
        
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
        $GLOBALS['invoiceStatus'] = '1';
        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            if ($request->customer_id == '0') {
                $customer = new Customer();
                $customer->name = $request->customer_name;
                $customer->email = $request->customer_email;
                $customer->phone = $request->customer_phone;
                $customer->status = $request->customer_type;
                $customer->created_at = Carbon::now();
                $customer->save();
                $customer_id = $customer->id;
            } else {
                $customer_id = $request->customer_id;
            }



            $invoice = new Invoice();
            $invoice->invoice_no = $request->invoice_no;
            $invoice->date = $request->date;
            $invoice->customer_id = $customer_id;
            $invoice->status = '0';
            $invoice->created_by = Auth::user()->id;
            $invoice->created_at = Carbon::now();
            $invoice->save();


            DB::transaction(function () use ($request, $invoice) {
                if ($invoice->save()) {
                    $count_category = count($request->category_id);

                    for ($i = 0; $i < $count_category; $i++) {

                        $invoice_details = new InvoiceDetail();
                        $invoice_details->date = date('Y-m-d', strtotime($request->date));
                        $invoice_details->invoice_id = $invoice->id;
                        $invoice_details->category_id = $request->category_id[$i];
                        $invoice_details->product_id = $request->product_id[$i];
                        $invoice_details->selling_qty = $request->selling_qty[$i];
                        $invoice_details->unit_price = $request->unit_price[$i];
                        $invoice_details->selling_price = $request->selling_price[$i];
                        $invoice_details->save();

                        $discount_per_qty = 0;
                        if ($request->discount_type != null) {
                            $discount_per_qty = round($request->discount_amount / $request->total_quantity, 2);
                        }

                        // // product stock updated
                        // $product = Product::where('id', $invoice_details->product_id)->first();
                        // if (((float) $request->selling_qty[$i]) > ((float) $product->quantity)) {
                        //     $GLOBALS['invoiceStatus'] = '0';
                        //     Invoice::findOrFail($invoice->id)->delete();
                        //     InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                        //     $errorNotification = array(
                        //         'message' => 'Sorry, Request Stock is not available',
                        //         'alert-type' => 'error',
                        //     );
                        //     return redirect()->back()->with($errorNotification);
                        // } else {
                        //     $product->quantity = ((float) $product->quantity) - ((float) $request->selling_qty[$i]);
                        //     $product->save();
                        //     $invoice_details->save();
                        //     // $invoice->status = '1';
                        //     // $invoice->save();
                        // }
                        $productInfo = PurchaseStore::where('product_id', $invoice_details->product_id)->where('quantity', '!=', 0)->get();
                        if ($productInfo->sum('quantity') >=  $invoice_details->selling_qty) {
                            $fifoStock = ((float) $request->selling_qty[$i]); //200 //165
                            foreach ($productInfo as  $purchaseInfo) {
                                $salesProfit = new SalesProfit();
                                $salesProfit->invoice_id = $invoice->id;
                                $salesProfit->purchase_id = $purchaseInfo->id;
                                $salesProfit->product_id = $request->product_id[$i];
                                $salesProfit->unit_price_purchase = (float) $purchaseInfo->unit_price;
                                $salesProfit->unit_price_sales =  (float) $request->unit_price[$i];
                                $salesProfit->date = $request->date;


                                if ((float) $request->selling_qty[$i] > (float)$purchaseInfo->quantity) {  //sellinh => 150  =>purchase=> 100
                                    $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity); // 200-35 = 165
                                    $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                                    $salesProfit->selling_qty =  (float) $purchaseInfo->quantity;  //35
                                    // $salesProfit->selling_qty =  (float) $request->selling_qty[$i];
                                    $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                                    $salesProfit->created_at = Carbon::now();
                                    $salesProfit->save();

                                    $purchaseInfo->update([
                                        'quantity' => 0,
                                    ]);
                                } else {
                                    $purchaseInfo->update([
                                        'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
                                    ]);

                                    $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                                    $salesProfit->selling_qty =  $fifoStock;
                                    $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                                    $salesProfit->created_at = Carbon::now();
                                    $salesProfit->save();
                                    break;
                                }
                            }
                        } else {
                            $GLOBALS['invoiceStatus'] = '0';
                            Invoice::findOrFail($invoice->id)->delete();
                            InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                            $allSales = SalesProfit::where('invoice_id', $invoice->id)->get();
                            foreach ($allSales as  $value) {
                                $purchaseStoreInfo = PurchaseStore::where('purchase_id', $value->purchase_id)
                                    ->where('product_id', $value->product_id)
                                    ->first();
                                $purchaseStoreInfo->quantity += $value->selling_qty;
                                $purchaseStoreInfo->update();
                            }

                            $notification = array(
                                'message' => 'Sorry, Request Stock is not available',
                                'alert-type' => 'error',
                            );
                            return redirect()->route('invoice.add')->with($notification);
                        }

                        // $productInfo = PurchaseStore::where('product_id', $invoice_details->product_id)->where('quantity', '!=', 0)->get();


                        // $fifoStock = ((float) $request->selling_qty[$i]); //200 //165
                        // foreach ($productInfo as  $purchaseInfo) {
                        //     $salesProfit = new SalesProfit();
                        //     $salesProfit->invoice_id = $invoice->id;
                        //     $salesProfit->purchase_id = $purchaseInfo->purchase_id;
                        //     $salesProfit->product_id = $request->product_id[$i];
                        //     $salesProfit->unit_price_purchase = (float) $purchaseInfo->unit_price;
                        //     $salesProfit->unit_price_sales =  (float) $request->unit_price[$i];


                        //     if ((float) $request->selling_qty[$i] > (float)$purchaseInfo->quantity) {  //sellinh => 150  =>purchase=> 100
                        //         $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity); // 200-35 = 165
                        //         $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                        //         $salesProfit->selling_qty =  (float) $purchaseInfo->quantity;  //35
                        //         // $salesProfit->selling_qty =  (float) $request->selling_qty[$i];
                        //         $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                        //         $salesProfit->created_at = Carbon::now();
                        //         $salesProfit->save();

                        //         $purchaseInfo->update([
                        //             'quantity' => 0,
                        //         ]);
                        //     } else {
                        //         $purchaseInfo->update([
                        //             'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
                        //         ]);

                        //         $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                        //         $salesProfit->selling_qty =  $fifoStock;
                        //         $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                        //         $salesProfit->created_at = Carbon::now();
                        //         $salesProfit->save();
                        //         break;
                        //     }
                        // }
                    }

                    if ($request->paid_source == 'bank') {
                        $bank_name = $request->bank_name;
                        $note = $request->check_number;
                    } else if ($request->paid_source == 'mobile-banking') {
                        $bank_name = $request->mobile_bank;
                        $note = $request->transaction_number;
                    } else if ($request->paid_source == 'online-banking') {
                        $note = $request->note;
                        $bank_name = NULL;
                    } else {
                        $bank_name = NULL;
                        $note = NULL;
                    }


                    $payment = new Payment();
                    $payment_details = new PaymentDetail();
                    $account_details = new AccountDetail();
                    $payment->invoice_id = $invoice->id;
                    $payment->customer_id = $invoice->customer_id;
                    $payment->paid_status = $request->paid_status;
                    // $payment->due_amount = $request->due_amount;
                    $payment->discount_amount = $request->discount_amount;
                    $payment->total_amount = $request->estimated_total;



                    $payment_details->paid_status = $request->paid_status;
                    $payment_details->paid_source = $request->paid_source;
                    $payment_details->bank_name = $bank_name;
                    $payment_details->note = $note;



                    // account details
                    $account_details->invoice_id = $invoice->id;
                    $account_details->customer_id = $invoice->customer_id;
                    $account_details->total_amount = $request->estimated_total;
                    $account_details->status = '1';
                    $account_details->date = date('Y-m-d', strtotime($request->date));
                    $account_details->paid_status = $request->paid_status;
                    $account_details->paid_source = $request->paid_source;
                    $account_details->bank_name = $bank_name;
                    $account_details->note = $note;


                    if ($request->paid_status == 'full_paid') {
                        $payment->paid_amount = $request->estimated_total;
                        $payment->due_amount = '0';
                        $payment_details->current_paid_amount = $request->estimated_total;

                        // account details
                        $account_details->paid_amount = $request->estimated_total;
                        $account_details->due_amount = '0';
                    } elseif ($request->paid_status == 'full_due') {
                        $payment->paid_amount = '0';
                        $payment->due_amount = $request->estimated_total;
                        $payment_details->current_paid_amount = '0';

                        //account details
                        $account_details->paid_amount = '0';
                        $account_details->due_amount = $request->estimated_total;
                    } elseif ($request->paid_status == 'partial_paid') {
                        $payment->paid_amount = $request->paid_amount;
                        $payment->due_amount = $request->estimated_total - $request->paid_amount;
                        $payment_details->current_paid_amount = $request->paid_amount;

                        //account details
                        $account_details->paid_amount = $request->paid_amount;
                        $account_details->due_amount = $request->estimated_total - $request->paid_amount;
                    }


                    $payment->save();

                    $payment_details->invoice_id = $invoice->id;
                    $payment_details->date = date('Y-m-d', strtotime($request->date));
                    $payment_details->save();
                    $invoice->status = '1';
                    $invoice->save();
                    $account_details->save();
                }
            });
        } //end else

        if ($GLOBALS['invoiceStatus'] == "0") {
            $notification = array(
                'message' => 'Sorry, Request Stock is not available',
                'alert-type' => 'error',
            );
            return redirect()->route('invoice.add')->with($notification);
        } else {
            $notification = array(
                'message' => 'Invoice Added Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('invoice.all')->with($notification);
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
        // dd($request->all());
        $GLOBALS['invoiceStatus'] = '1';

        $invoice_id = $request->id;
        $invoice = Invoice::findOrFail($invoice_id);



        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

            Invoice::findOrFail($invoice_id)->update([
                'invoice_no' => $request->invoice_no,
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'updated_by' => Auth::user()->id,
            ]);





            /** ============ Start update existing product sales information ===== */

            $salesProducts = SalesProfit::where('invoice_id', $invoice_id)->get();
            foreach ($salesProducts as $product) {
                $purchaseStoreInfo = PurchaseStore::where('purchase_id', $product->purchase_id)->where('product_id', $product->product_id)->first();
                $purchaseStoreInfo->quantity += $product->selling_qty;
                $purchaseStoreInfo->update();
                $product->delete();
            }

            foreach ($invoice->invoice_details as $item) {
                $products = Product::where('id', $item->product_id)->first();
                $products->quantity = ((float) $products->quantity) + ((float) $item->selling_qty);
                $products->update([
                    'quantity' => $products->quantity,
                ]);
                $item->delete();
            }

            Payment::where('invoice_id', $invoice_id)->delete();
            $paymentDetails = PaymentDetail::where('invoice_id', $invoice_id)->get();
            $accountDetails = AccountDetail::where('invoice_id', $invoice_id)->get();
            foreach ($paymentDetails as $item) {
                $item->delete();
            }
            foreach ($accountDetails as $account) {
                $account->delete();
            }

            /** ============  End update existing product sales information ========*/


            $count_category = count($request->category_id);

            for ($i = 0; $i < $count_category; $i++) {

                $invoice_details = new InvoiceDetail();
                $invoice_details->date = date('Y-m-d', strtotime($request->date));
                $invoice_details->invoice_id = $invoice->id;
                $invoice_details->category_id = $request->category_id[$i];
                $invoice_details->product_id = $request->product_id[$i];
                $invoice_details->selling_qty = $request->selling_qty[$i];
                $invoice_details->unit_price = $request->unit_price[$i];
                $invoice_details->selling_price = $request->selling_price[$i];
                $invoice_details->save();

                 $productInfo = PurchaseStore::where('product_id', $invoice_details->product_id)->where('quantity', '!=', 0)->get();


                if ($productInfo->sum('quantity') >=  $invoice_details->selling_qty) {
                    $fifoStock = ((float) $request->selling_qty[$i]);
                    foreach ($productInfo as  $purchaseInfo) {
                        $salesProfit = new SalesProfit();
                        $salesProfit->invoice_id = $invoice->id;
                        $salesProfit->purchase_id = $purchaseInfo->purchase_id;
                        $salesProfit->product_id = $request->product_id[$i];
                        $salesProfit->unit_price_purchase = (float) $purchaseInfo->unit_price;
                        $salesProfit->unit_price_sales =  (float) $request->unit_price[$i];
                        $salesProfit->date = $request->date;


                        if ((float) $request->selling_qty[$i] > (float)$purchaseInfo->quantity) {  
                            $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity); 
                            $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                            $salesProfit->selling_qty =  (float) $purchaseInfo->quantity;
                            $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                            $salesProfit->created_at = Carbon::now();
                            $salesProfit->save();

                            $purchaseInfo->update([
                                'quantity' => 0,
                            ]);
                        } else {
                            $purchaseInfo->update([
                                'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
                            ]);

                            $salesProfit->profit_per_unit =  (float) $request->unit_price[$i] -  (float)$purchaseInfo->unit_price;
                            $salesProfit->selling_qty =  $fifoStock;
                            $salesProfit->profit = $salesProfit->profit_per_unit * $salesProfit->selling_qty;
                            $salesProfit->created_at = Carbon::now();
                            $salesProfit->save();
                            break;
                        }
                    }
                }else{
                    $GLOBALS['invoiceStatus'] = '0';
                    Invoice::findOrFail($invoice->id)->delete();
                    InvoiceDetail::where('invoice_id', $invoice->id)->delete();
                    $allSales = SalesProfit::where('invoice_id', $invoice->id)->get();
                    foreach ($allSales as  $value) {
                        $purchaseStoreInfo = PurchaseStore::where('purchase_id', $value->purchase_id)
                            ->where('product_id', $value->product_id)
                            ->first();
                        $purchaseStoreInfo->quantity += $value->selling_qty;
                        $purchaseStoreInfo->update();
                    }

                    $notification = array(
                        'message' => 'Sorry, Request Stock is not available',
                        'alert-type' => 'error',
                    );
                    return redirect()->route('invoice.add')->with($notification);

                }
            }

            if ($request->paid_source == 'bank') {
                $bank_name = $request->bank_name;
                $note = $request->check_number;
            } else if ($request->paid_source == 'mobile-banking') {
                $bank_name = $request->mobile_bank;
                $note = $request->transaction_number;
            } else if ($request->paid_source == 'online-banking') {
                $note = $request->note;
                $bank_name = NULL;
            } else {
                $bank_name = NULL;
                $note = NULL;
            }



            $payment = new Payment();
            $payment_details = new PaymentDetail();
            $account_details = new AccountDetail();
            $payment->invoice_id = $invoice->id;
            $payment->customer_id = $invoice->customer_id;
            $payment->paid_status = $request->paid_status;
            // $payment->due_amount = $request->due_amount;
            $payment->discount_amount = $request->discount_amount;
            $payment->total_amount = $request->estimated_total;



            $payment_details->paid_status = $request->paid_status;
            $payment_details->paid_source = $request->paid_source;
            $payment_details->bank_name = $bank_name;
            $payment_details->note = $note;



            // account details
            $account_details->invoice_id = $invoice->id;
            $account_details->customer_id = $invoice->customer_id;
            $account_details->total_amount = $request->estimated_total;
            $account_details->date = date('Y-m-d', strtotime($request->date));
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->note = $note;


            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = $request->estimated_total;
                $payment->due_amount = '0';
                $payment_details->current_paid_amount = $request->estimated_total;

                // account details
                $account_details->paid_amount = $request->estimated_total;
                $account_details->due_amount = '0';
            } elseif ($request->paid_status == 'full_due') {
                $payment->paid_amount = '0';
                $payment->due_amount = $request->estimated_total;
                $payment_details->current_paid_amount = '0';

                //account details
                $account_details->paid_amount = '0';
                $account_details->due_amount = $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = $request->paid_amount;
                $payment->due_amount = $request->estimated_total - $request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;

                //account details
                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = $request->estimated_total - $request->paid_amount;
            }


            $payment->save();

            $payment_details->invoice_id = $invoice->id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->save();
            $account_details->save();
        }

        if ($GLOBALS['invoiceStatus'] == '0') {
            $notification = array(
                'message' => 'Sorry, Request Stock is not available',
                'alert-type' => 'error',
            );
            return redirect()->route('invoice.add')->with($notification);
        } else {
            $notification = array(
                'message' => 'Invoice Updated Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->route('invoice.all')->with($notification);
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
        $invoice = Invoice::findOrFail($id);

        foreach ($invoice->invoice_details as $item) {
            $products = Product::where('id', $item->product_id)->first();
            $products->quantity = ((float) $products->quantity) + ((float) $item->selling_qty);
            $products->update();
            $item->delete();
        }

        Payment::where('invoice_id', $invoice->id)->delete();
        PaymentDetail::where('invoice_id', $invoice->id)->delete();
        AccountDetail::where('invoice_id', $invoice->id)->delete();

        $salesProducts = SalesProfit::where('invoice_id',  $invoice->id)->get();
        foreach ($salesProducts as $sale) {
            $purchaseStoreInfo = PurchaseStore::where('id', $sale->purchase_id)->first();
            if($purchaseStoreInfo){
                $purchaseStoreInfo->quantity = $purchaseStoreInfo->quantity + $sale->selling_qty;
                $purchaseStoreInfo->update();
            }
            $sale->delete();
        }

        $invoice->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    public function UniqueNumber()
    {
        $invoice = Invoice::latest()->first();
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

    public function DynamicQuery(Request $request){
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
                if($purchaseStoreInfo){
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
