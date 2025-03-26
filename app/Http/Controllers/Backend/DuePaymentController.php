<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DuePayment;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\AccountDetail;
use App\Models\Invoice;
use App\Models\DuePaymentDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DuePaymentController extends Controller
{
    public function AllDuePayment()
    {
        $dueAll = DuePayment::get();
        return view('admin.due_payment.all_due', compact('dueAll'));
    }


    public function AddDuePayment()
    {
        $companies = Customer::get();
        return view('admin.due_payment.add_due', compact('companies'));
    }

    public function StoreDuePayment(Request $request)
    {
        $company_id = $request->company_id;
        $companyInfo = Customer::where('id', $company_id)->first();

        if ($request->paid_amount > $request->due_amount) {
            return redirect()->back()->with([
                'message' => 'Sorry, Paid amount is greater than the due amount!',
                'alert-type' => 'error',
            ]);
        }

        try {
            // Save payment request without modifying any balances
            $due_payment = new DuePayment();
            $due_payment->customer_id = $company_id;
            $due_payment->paid_amount = $request->paid_amount;
            $due_payment->date = $request->date;
            $due_payment->paid_status = $request->paid_status;
            $due_payment->status = 'approved'; 
            $due_payment->approved_at = $request->date; 
            $due_payment->save();

            // Get account details
            $account_details = AccountDetail::where('customer_id', $company_id)->latest('id')->first();
            $due_amount = Payment::where('customer_id', $company_id)->sum('due_amount');
            $account_balance = $account_details->balance ?? $due_amount;

            // Update account balance
            $account_details = new AccountDetail();
            $account_details->total_amount = 0;
            $account_details->paid_amount = $request->paid_amount;
            $account_details->due_amount = $account_balance - $request->paid_amount;
            $account_details->customer_id = $company_id;

            $account_details->due_payment_id = $due_payment->id;
            $account_details->date = $request->date;
            $account_details->balance = $account_balance - $request->paid_amount;
            $account_details->save();

            DB::commit();

            $notification = array(
                'message' => 'Due Payment is Added Successfully!',
                'alert-type' => 'success',
            );
            return redirect()->route('all.due.payment')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('StoreDuePayment error: ' . $e->getMessage() . ' Line: ' . $e->getLine());
            return back()->with([
                'message' => 'Something went wrong!',
                'alert-type' => 'error',
            ]);
        }
    }

    public function EditDuePayment($id)
    {
        $due_payment_info = DuePayment::findOrFail($id);
        $companies = Customer::get();

        $payment_due_amount = Payment::where('customer_id', $due_payment_info->customer_id)->sum('due_amount');

        $account_details = AccountDetail::where('customer_id', $due_payment_info->customer_id)
            ->where('due_payment_id', $id)
            ->latest('id')
            ->first();
        
        $due_amount = $account_details->balance ?? $payment_due_amount;
        $companyInfo = Customer::where('id', $due_payment_info->customer_id)->first();

        return view('admin.due_payment.edit_due', compact('due_payment_info', 'companies', 'due_amount', 'companyInfo'));
    }

    private function resetDuePayment($due_payment)
    {        
        $accountDetail = AccountDetail::where('customer_id', $due_payment->customer_id)
            ->where('due_payment_id', $due_payment->id)
            ->first();
        
        if ($accountDetail) {
            $nextAccountDetails = AccountDetail::where('customer_id', $due_payment->customer_id)
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

    public function UpdateDuePayment(Request $request)
    {
        DB::beginTransaction();
        try {
                $id = $request->id;
                $due_payment = DuePayment::findOrFail($id);
                $company_id = $due_payment->customer_id;
                $companyInfo = Customer::where('id', $company_id)->first();

                

                // Save payment request without modifying any balances
                $due_payment->customer_id = $company_id;
                $due_payment->paid_amount = $request->paid_amount;
                $due_payment->date = $request->date;
                $due_payment->paid_status = $request->paid_status;
                $due_payment->status = 'approved'; 
                $due_payment->approved_at = $request->date; 
                $due_payment->save();

                // Get account details
                $account_details = AccountDetail::where('customer_id', $company_id)->where('due_payment_id', $id)->latest('id')->first();
                $due_amount = Payment::where('customer_id', $company_id)->sum('due_amount');
                $account_balance = ($account_details->balance + $account_details->paid_amount) ?? $due_amount;

                // Update account balance
                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = $account_balance - $request->paid_amount;
                $account_details->customer_id = $company_id;
                $account_details->due_payment_id = $due_payment->id;
                $account_details->date = $request->date;
                $account_details->balance = $account_balance - $request->paid_amount;
                $account_details->save();

                $this->resetDuePayment($due_payment);

                DB::commit();

            $notification = array(
                    'message' => 'Due Payment Successfully Updated!',
                    'alert_type' => 'success',
                );
            return redirect()->route('all.due.payment')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating due payment: ' . $e->getMessage());
            $notification = array(
                'message' => 'An error occurred while updating the due payment.',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
    }


    public function DeleteDuePayment($id)
    {
        $due_payment = DuePayment::findOrFail($id);
        $companyInfo = Customer::where('id', $due_payment->customer_id)->first();
        
        $due_payment_details = DuePaymentDetail::where('due_payment_id', $due_payment->id)->get();

        $accountDetail = AccountDetail::where('customer_id', $due_payment->customer_id)
            ->where('due_payment_id', $id)
            ->where('paid_amount', $due_payment->paid_amount)
            ->where('date', $due_payment->date)
            ->first();
        
        if ($accountDetail) {
            $nextAccountDetail = AccountDetail::where('customer_id', $due_payment->customer_id)
                ->where('id', '>', $accountDetail->id)
                ->get();
        
            foreach ($nextAccountDetail as $next) {
                $next->balance = $next->balance + $due_payment->paid_amount;
                $next->save();
            }

            $accountDetail->delete();
        }

        $due_payment->delete();

        $notification = array(
            'message' => 'Due Payment Successfully Deleted!',
            'alert_type' => 'success',
        );

       return redirect()->route('all.due.payment')->with($notification);
    }


    public function GetDuePayment(Request $request)
    {
        $customerInfo = Customer::where('id', $request->company_id)->first();

        $payment_due_amount = Payment::where('customer_id', $request->company_id)->sum('due_amount');

        $accountBill = AccountDetail::where('customer_id', $customerInfo->id)->latest('id')->first();
        $due_amount = $accountBill->balance ?? $payment_due_amount;

        $invoiceAll = NULL;

        return response()->json(
            [
                'due_amount' => $due_amount,
                'invoice' => $invoiceAll
            ]
        );
    }


    public function DuePaymentApproval(){
        $dueAll = DuePayment::where('status', 'pending')->get();
        return view('admin.due_payment.due_payment_approval', compact('dueAll'));
    }


    public function DuePaymentApprovalNow($id)
    {
        $due_payment = DuePayment::findOrFail($id);

        $company_id = $due_payment->customer_id;
        $companyInfo = Company::where('id', $company_id)->first();

        $due_payment_details = DuePaymentDetail::where('due_payment_id', $due_payment->id)->get();

        if ($due_payment->approved_at) {
            return redirect()->route('due.payment.approval')->with([
                'message' => 'This Due Payment is already approved!',
                'alert-type' => 'warning',
            ]);
        }

        $company_id = $due_payment->customer_id;
        $total_paid_amount = $due_payment->paid_amount;

        // Get account details
        $account_details = AccountDetail::where('company_id', $company_id)->latest('id')->first();
        $due_amount = Payment::where('company_id', $company_id)->sum('due_amount');
        $account_balance = $account_details->balance ?? $due_amount;

        // Update account balance
        $account_details = new AccountDetail();
        $account_details->paid_amount = $due_payment->paid_amount;
        $account_details->company_id = $company_id;
        $account_details->date = $due_payment->date;
        $account_details->voucher = $due_payment->voucher;
        $account_details->balance = $account_balance - $due_payment->paid_amount;
        if ($companyInfo->status == '1') {
            $account_details->status = '1';
        } elseif ($companyInfo->status == '0') {
            $account_details->status = '0';
        }
        $account_details->save();

        // Update payment and due amount
        foreach ($due_payment_details as $detail) { 
            $payment = Payment::where('invoice_id', $detail->invoice_id)->first();
            
            if ($payment) {
            $payment->paid_amount = min($payment->total_amount, $payment->paid_amount + $total_paid_amount);
            $payment->due_amount = max(0, $payment->total_amount - $payment->paid_amount);
            $payment->save();

            $total_paid_amount -= $payment->paid_amount;
            if ($total_paid_amount <= 0) break; // Breaks the loop if no amount left to distribute
            }
        }
        

        // Mark the due payment as approved
        $due_payment->approved_at = now();
        $due_payment->status = 'approved';
        $due_payment->save();

        if ($companyInfo->status == '1') {
            $notification = array(
                'message' => 'Due Payment Successfully Deleted!',
                'alert_type' => 'success',
            );

            return redirect()->route('all.corporate.due.payment')->with($notification);
        } elseif ($companyInfo->status == '0') {   

            $notification = array(
                'message' => 'Due Payment Successfully Deleted!',
                'alert_type' => 'success',
            );

        return redirect()->route('all.due.payment')->with($notification);
      }
    }

}
