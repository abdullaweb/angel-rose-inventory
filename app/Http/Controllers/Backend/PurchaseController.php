<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseMeta;
use App\Models\PurchaseStore;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Configuration\Php;

class PurchaseController extends Controller
{
    public function AllPurchase()
    {
        $allPurchase = Purchase::latest()->get();
        return view('admin.purchase_page.all_purchase', compact('allPurchase'));
    }

    public function AddPurchase()
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $products = Product::all();
        $purchase_no = $this->UniqueNumber();
        $allBank = Bank::OrderBy('name', 'asc')->get();
        return view('admin.purchase_page.add_purchase', compact('purchase_no', 'suppliers', 'categories', 'products', 'allBank'));
    }

    public function StorePurchase(Request $request)
    {
        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {
            $purchase = new Purchase();
            $purchase->purchase_no = $request->purchase_no;
            $purchase->date = $request->date;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->total_amount = $request->estimated_total;

            $purchase->created_by = Auth::user()->id;
            $purchase->created_at = Carbon::now();


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



            // save sata to supplier account
            $account_details = new SupplierAccount();
            $account_details->supplier_id = $request->supplier_id;
            $account_details->total_amount = $request->estimated_total;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->note = $note;
            $account_details->date = date('Y-m-d', strtotime($request->date));
            $account_details->created_at = Carbon::now();



            if ($request->paid_status == 'full_paid') {
                $purchase->paid_amount = $request->estimated_total;
                $purchase->due_amount = '0';

                // save data to accounts
                $account_details->paid_amount = $request->estimated_total;
                $account_details->due_amount = '0';    
            } elseif ($request->paid_status == 'full_due') {
                $purchase->paid_amount =  '0';
                $purchase->due_amount = $request->estimated_total;

                // save data to accounts
                $account_details->paid_amount = '0';
                $account_details->due_amount = $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                $purchase->paid_amount = $request->paid_amount;
                $purchase->due_amount = (float) $request->estimated_total - (float)$request->paid_amount;

                // save data to accounts
                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = (float)$request->estimated_total - (float)$request->paid_amount;
            }

            $purchase->save();
            $account_details->purchase_id = $purchase->id;
            $account_details->save();

            for ($i = 0; $i < count($request->category_id); $i++) {
                $purchaseMeta = new PurchaseMeta();
                $purchaseMeta->purchase_id = $purchase->id;
                $purchaseMeta->category_id = $request->category_id[$i];
                $purchaseMeta->product_id = $request->product_id[$i];
                $purchaseMeta->quantity = $request->quantity[$i];
                $purchaseMeta->unit_price = $request->unit_price[$i];
                $purchaseMeta->total = $purchaseMeta->quantity * $purchaseMeta->unit_price;
                $purchaseMeta->created_at = Carbon::now();
                $purchaseMeta->save();


                $purchaseStore = new PurchaseStore();
                $purchaseStore->product_id = $purchaseMeta->product_id;
                $purchaseStore->purchase_id =  $purchase->id;
                $purchaseStore->quantity  = $purchaseMeta->quantity;
                $purchaseStore->unit_price = $purchaseMeta->unit_price;
                $purchaseStore->created_at = Carbon::now();
                $purchaseStore->save();


                // product quantity update
                $product = Product::where('id', $purchaseMeta->product_id)->first();
                $purchase_qty = ((float)($purchaseMeta->quantity)) + ((float)($product->quantity));
                $product->quantity = $purchase_qty;
                $product->save();
            }

            $notification = array(
                'message' => 'Purchase Addedd Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.purchase')->with($notification);
        }
    }


    public function EditPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::OrderBy('name', 'asc')->get();
        $categories = Category::all();
        $products = Product::all();
        $allBank = Bank::OrderBy('name', 'asc')->get();
        return view('admin.purchase_page.edit_purchase', compact('purchase', 'suppliers', 'categories', 'products', 'allBank'));
    }

    public function UpdatePurchase(Request $request)
    {
        $purchase_id = $request->id;
        $purchase = Purchase::findOrFail($purchase_id);


        if ($request->paid_amount > $request->estimated_total) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the total amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

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


            function purchaseUpdate($purchase)
            {

                PurchaseStore::where('purchase_id', $purchase->id)->delete();
                SupplierAccount::where('purchase_id', $purchase->id)->delete();
                foreach ($purchase->purchaseMeta as $item) {
                    // product quantity update
                    $product = Product::where('id', $item->product_id)->first();
                    $purchase_qty = $product->quantity - $item->quantity;
                    $product->quantity = $purchase_qty;
                    $product->update();

                    $item->delete();
                }
            };



            // store update data to database



            $purchase->date = $request->date;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->total_amount = $request->estimated_total;
            $purchase->created_by = Auth::user()->id;



            $account_details = new SupplierAccount();
            $account_details->purchase_id = $purchase_id;
            $account_details->supplier_id = $request->supplier_id;
            $account_details->total_amount = $request->estimated_total;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->note = $note;
            $account_details->date = date('Y-m-d', strtotime($request->date));
            $account_details->created_at = Carbon::now();

          
            if ($request->paid_status == 'full_paid') {
                $purchase->paid_amount = $request->estimated_total;
                $purchase->due_amount = '0';

                // save data to accounts
                $account_details->paid_amount = $request->estimated_total;
                $account_details->due_amount = '0';


                purchaseUpdate($purchase);

            } elseif ($request->paid_status == 'full_due') {
                purchaseUpdate($purchase);
                $purchase->paid_amount =  '0';
                $purchase->due_amount = $request->estimated_total;

                // save data to accounts
                $account_details->paid_amount = '0';
                $account_details->due_amount = $request->estimated_total;
            } elseif ($request->paid_status == 'partial_paid') {
                purchaseUpdate($purchase);
                $purchase->paid_amount = $request->paid_amount;
                $purchase->due_amount = (float) $request->estimated_total - (float)$request->paid_amount;

                // save data to accounts
                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = (float)$request->estimated_total - (float)$request->paid_amount;
            }


            $purchase->update();
            $account_details->save();

            for ($i = 0; $i < count($request->category_id); $i++) {
                $purchaseMeta = new PurchaseMeta();
                $purchaseMeta->purchase_id = $purchase->id;
                $purchaseMeta->category_id = $request->category_id[$i];
                $purchaseMeta->product_id = $request->product_id[$i];
                $purchaseMeta->quantity = $request->quantity[$i];
                $purchaseMeta->unit_price = $request->unit_price[$i];
                $purchaseMeta->total = $purchaseMeta->quantity * $purchaseMeta->unit_price;
                $purchaseMeta->created_at = Carbon::now();
                $purchaseMeta->save();


                $purchaseStore = new PurchaseStore();
                $purchaseStore->product_id = $purchaseMeta->product_id;
                $purchaseStore->purchase_id =  $purchase->id;
                $purchaseStore->quantity  = $purchaseMeta->quantity;
                $purchaseStore->unit_price = $purchaseMeta->unit_price;
                $purchaseStore->created_at = Carbon::now();
                $purchaseStore->save();


                // product quantity update
                $product = Product::where('id', $purchaseMeta->product_id)->first();
                $purchase_qty = ((float)($purchaseMeta->quantity)) + ((float)($product->quantity));
                $product->quantity = $purchase_qty;
                $product->save();
            }

            $notification = array(
                'message' => 'Purchase Updated Successfully',
                'alert_type' => 'success'
            );
            return redirect()->route('all.purchase')->with($notification);
        }
    }

    public function ViewPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('admin.purchase_page.purchase_view', compact('purchase'));
    }


    public function DeletePurchase($id)
    {
        $purchase = Purchase::findOrFail($id);
        // $bank_details = BankDetail::where('trans_id', $purchase->purchase_no)->first();
        // $bank = Bank::findOrFail($bank_details->bank_id);
        // $bank->balance += (float) $purchase->paid_amount;
        // $bank->update();
        // $bank_details->delete();

        SupplierAccount::where('purchase_id', $id)->delete();

        foreach ($purchase->purchaseMeta as $item) {
            $item->delete();
        }

        $purchase->delete();


        $notification = array(
            'message' => 'Purchase Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }


    public function UniqueNumber()
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

    public function GetProduct(Request $request)
    {
        $id =  $request->id;
        $supplier_id = $request->supplier_id;

        $products = Product::where('category_id', $id)->where('supplier_id', $supplier_id)->get();
        return response()->json($products);
    }




    // due payment
    public function PurchaseDuePayment($id)
    {
        $purchaseInfo = Purchase::findOrFail($id);
        $supplierInfo = Supplier::where('id', $purchaseInfo->supplier_id)->first();
        return view('admin.purchase_page.purchase_due_payment', compact('purchaseInfo', 'supplierInfo'));
    }


    public function PurchaseDuePaymentStore(Request $request)
    {
        $purchase_id = $request->id;
        // dd($request->all());

        $supplierInfo = SupplierAccount::where('supplier_id', $request->supplier_id)->get();
        $due_amount = $supplierInfo->sum('total_amount') - $supplierInfo->sum('paid_amount');

        if ($request->paid_amount > $request->due_amount) {
            $notification = array(
                'message' => 'Sorry, Paid amount is maximum the due amount',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {

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


            $account_details = new SupplierAccount();
            $purchase = Purchase::findOrfail($purchase_id);
            $current_paid_amount = $purchase->paid_amount;
            $current_due_amount = $purchase->due_amount;

            $account_details->supplier_id = $request->supplier_id;
            $account_details->purchase_id = $purchase_id;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->status = '0';
            $account_details->note = $note;
            $account_details->date = date('Y-m-d', strtotime($request->date));

     
            if ($request->paid_status == 'full_paid') {
                $account_details->paid_amount = $request->due_amount;
                $account_details->due_amount = '0';
                $purchase->update([
                    'paid_amount' => $current_paid_amount + $request->due_amount,
                    'due_amount' => '0',
                ]);
            } elseif ($request->paid_status == 'partial_paid') {
                $account_details->paid_amount = $request->paid_amount;
                $new_paid_amount = $current_paid_amount + $request->paid_amount;
                $account_details->due_amount = $request->total_amount - $new_paid_amount;
                $purchase->update([
                    'paid_amount' => $new_paid_amount,
                    'due_amount' => $current_due_amount - $request->paid_amount,
                ]);
            }

            $account_details->save();

            $notification = array(
                'message' => 'Payment Updated Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->back()->with($notification);
        }
    }

    public function PurchasePrint($id)
    {
        $purchase = Purchase::with('purchaseMeta')->findOrFail($id);
        $purchaseMeta = PurchaseMeta::where('purchase_id', $purchase->id)->get();
        return view('admin.pdf.purchase_pdf', compact('purchase', 'purchaseMeta'));
    }

    public function PurchaseHistory($id)
    {
        $purchase = PurchaseMeta::where('product_id', $id)->get();
        $product = Product::findOrFail($id);
        return view('admin.purchase_page.purchase_history', compact('purchase', 'product'));
    }
}
