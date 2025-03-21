<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function SupplierAll()
    {
        $supplierAll = Supplier::latest()->get();
        return view('admin.supplier.supplier_all', compact('supplierAll'));
    } //end method

    public function SupplierAdd()
    {
        return view('admin.supplier.supplier_add');
    } //end method

    public function SupplierStore(Request $request)
    {
        Supplier::insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message' => 'Supplier Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('supplier.all')->with($notification);
    }

    public function SupplierEdit($id)
    {
        $supplierInfo = Supplier::findOrFail($id);
        return view('admin.supplier.supplier_edit', compact('supplierInfo'));
    }

    public function SupplierUpdate(Request $request)
    {
        $supplierId  =  $request->id;
        Supplier::findOrFail($supplierId)->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'address' => $request->address,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('supplier.all')->with($notification);
    } //end method

    public function SupplierDelete($id)
    {

        Supplier::findOrFail($id)->delete();
        Purchase::where('supplier_id', $id)->delete();


        $notification = array(
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }




    // supplier purchase
    public function SupplierBill($id)
    {
        $purchaseData = Purchase::orderBy('date', 'desc')->orderBy('purchase_no', 'desc')->where('supplier_id', $id)->get();
        return view('admin.supplier.supplier_invoice', compact('purchaseData', 'id'));
    }

    public function SupplierAccountDetails($id)
    {
        $accountDetails = SupplierAccount::orderBy('date', 'asc')->where('supplier_id', $id)->get();
        $supplierInfo = Supplier::where('id', $id)->first();
        return view('admin.supplier.supplier_account_details', compact('accountDetails', 'supplierInfo'));
    }

    public function SupplierAccountDetailReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $supplier_id = $request->supplier_id;

        if ($start_date == null && $end_date == null) {
            $billDetails = SupplierAccount::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $billDetails = SupplierAccount::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])->where('supplier_id', $request->supplier_id)
                ->get();
        }
        return view('admin.report.supplier_account_detials_report', compact('billDetails', 'start_date', 'end_date', 'supplier_id'));
    }



    // opening balance method
    public function AllOpeningBalance()
    {
        $allOpening = SupplierAccount::where('status', '2')->get();
        return view('admin.supplier.opening_balance.all_opening', compact('allOpening'));
    }
    public function AddOpeningBalance()
    {
        $suppliers = Supplier::OrderBy('name', 'asc')->where('status', '1')->get();
        return view('admin.supplier.opening_balance.add_opening', compact('suppliers'));
    }

    public function StoreOpeningBalance(Request $request)
    {

        $exitingSupplier = SupplierAccount::where('supplier_id', $request->supplier_id)->where('status', '2')->first();
        if ($exitingSupplier) {
            $notification = array(
                'message' => 'Opening Balance Already Added!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {
            $account_details = new SupplierAccount();
            $account_details->total_amount = $request->total_amount;
            $account_details->paid_amount = $request->paid_amount;
            $account_details->due_amount = $request->total_amount - $request->paid_amount;
            $account_details->supplier_id = $request->supplier_id;
            $account_details->date = date('Y-m-d', strtotime($request->date));
            $account_details->status = '2';
            $account_details->save();

            $notification = array(
                'message' => 'Opening Balance Added Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->route('all.opening.supplier')->with($notification);
        }
    }


    public function EditOpeningBalance($id)
    {
        $accountInfo = SupplierAccount::findOrFail($id);
        $suppliers = Supplier::where('status', '1')->OrderBy('name', 'asc')->get();
        return view('admin.supplier.opening_balance.edit_opening', compact('accountInfo', 'suppliers'));
    }

    public function UpdateOpeningBalance(Request $request)
    {

        $accountId = $request->id;

        SupplierAccount::findOrFail($accountId)->update([
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'supplier_id' => $request->supplier_id,
            'due_amount' => $request->total_amount - $request->paid_amount,
            'date' => date('Y-m-d', strtotime($request->date)),
        ]);
        $notification = array(
            'message' => 'Opening Balance Updated Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.opening.supplier')->with($notification);
    }


    public function DeleteOpeningBalance($id)
    {
        SupplierAccount::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Balance Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.opening.supplier')->with($notification);
    }

    // bill wise opeinig balance  added
}
