<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function AllBank()
    {
        $allBank = Bank::OrderBy('name', 'asc')->get();
        $title = 'All Bank';
        return view('admin.accounts.bank.all_bank', compact('allBank', 'title'));
    }

    public function AddBank()
    {
        $title = 'Add Bank';
        return view('admin.accounts.bank.add_bank', compact('title'));
    }

    public function StoreBank(Request $request)
    {
        $validated = $request->validate(
            [
                'account_number' => 'required|unique:banks|max:255',
            ],
            [
                'account_number' => 'Account number already exits!',
            ]
        );

        $bank = new Bank();
        $bank->name = $request->name;
        $bank->account_number = $request->account_number;
        $bank->branch_name = $request->branch_name;
        $bank->balance = $request->opening_balance;
        $bank->status = '1';
        $bank->created_at = Carbon::now();
        $bank->save();


        $bankDetails = new BankDetail();
        $bankDetails->bank_id = $bank->id;
        $bankDetails->trans_head = 'Opening Balance';
        $bankDetails->balance = $request->opening_balance;
        $bankDetails->status = '1';
        $bankDetails->date = Carbon::now();
        $bankDetails->created_at = Carbon::now();
        $bankDetails->save();

        $notification = array(
            'message' => 'Bank Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.bank')->with($notification);
    }

    public function EditBank($id)
    {
        $title = 'Update Bank';
        $bankInfo = Bank::findOrFail($id);
        return view('admin.accounts.bank.edit_bank', compact('title', 'bankInfo'));
    }

    public function UpdateBank(Request $request)
    {
        $bankId = $request->id;
        $bank =  Bank::findOrFail($bankId);
        $bank->name = $request->bank_name;
        $bank->account_number = $request->account_number;
        $bank->branch_name = $request->branch_name;
        $bank->balance = $request->opening_balance;
        $bank->update();


        $bankDetails = BankDetail::where('bank_id', $bankId)->where('trans_head', 'Opening Balance')->first();
        $bankDetails->balance = $request->opening_balance;
        $bankDetails->update();

        $notification = array(
            'message' => 'Bank Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.bank')->with($notification);
    }

    public function DetailsBank($id)
    {
        $bankInfo = Bank::findOrFail($id);
        $bankDetails = BankDetail::where('bank_id', $id)->get();
        return view('admin.accounts.bank.bank_details', compact('bankDetails', 'bankInfo'));
    }

    public function DeleteBank($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        $notification = array(
            'message' => 'Bank Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.bank')->with($notification);
    }
}
