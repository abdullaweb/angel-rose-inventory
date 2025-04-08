<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\BillPayment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function CustomerAll()
    {
        $allData = Customer::where('status', '0')->get();
        $title = 'Customer';
        return view('admin.customer_page.all_customer', compact('allData', 'title'));
    }
    public function WholesalerAll()
    {
        $allData = Customer::where('status', '1')->get();
        $title = 'Wholesaler';
        return view('admin.customer_page.all_customer', compact('allData', 'title'));
    }
    public function CustomerAdd()
    {
        return view('admin.customer_page.add_customer');
    }

    public function CustomerStore(Request $request)
    {
        $company = Customer::orderBy('id', 'desc')->first();
        if ($company == null) {
            $firstReg = '0';
            $companyId = $firstReg + 1;
        } else {
            $company = Customer::orderBy('id', 'desc')->first()->id;
            $companyId = $company + 1;
        }
        // dd($companyId);
        if ($companyId < 10) {
            $id_no = '000' . $companyId; //0009
        } elseif ($companyId < 100) {
            $id_no = '00' . $companyId; //0099
        } elseif ($companyId < 1000) {
            $id_no = '0' . $companyId; //0999
            $id_no = '0' . $companyId; //0999
        } else {
            $id_no = $companyId;
        }

        $check_year = date('Y');

        $name = $request->name;
        $words = explode(' ', $name);
        $acronym = '';
        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }

        $company_id = $acronym . '-' . $check_year . '.' . $id_no;

        Customer::insert([
            'name' => $request->name,
            'company_id' => $company_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'address' => $request->address,
            'status' => $request->customer_type,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Added Successfully',
            'alert-type' => 'success'
        );
        if ($request->customer_type == '0') {
            return redirect()->route('all.customer')->with($notification);
        } else {
            return redirect()->route('all.wholesaler')->with($notification);
        }
    } //end method


    public function CustomerEdit($id)
    {
        $customerInfo = Customer::findOrFail($id);
        return view('admin.customer_page.edit_customer', compact('customerInfo'));
    }

    public function CustomerUpdate(Request $request)
    {
        $customerId = $request->id;
        Customer::findOrFail($customerId)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'telephone' => $request->telephone,
            'address' => $request->address,
            'status' => $request->customer_type,
        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function CustomerDelete($id)
    {

        $invoiceInfo = Invoice::where('customer_id', $id)->get();

        foreach ($invoiceInfo as $invoice) {
            PaymentDetail::where('invoice_id', $invoice->id)->delete();
        }

        Customer::findOrFail($id)->delete();
        Invoice::where('company_id', $id)->delete();
        InvoiceDetail::where('company_id', $id)->delete();
        Payment::where('company_id', $id)->delete();
        AccountDetail::where('company_id', $id)->delete();

        $notification = array(
            'message' => 'Company Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.company')->with($notification);
    }


    public function CustomerBillDelete($id)
    {
        $invoiceInfo = Invoice::where('company_id', $id)->get();

        foreach ($invoiceInfo as $invoice) {
            PaymentDetail::where('invoice_id', $invoice->id)->delete();
        }

        Invoice::where('company_id', $id)->delete();
        InvoiceDetail::where('company_id', $id)->delete();
        Payment::where('company_id', $id)->delete();
        AccountDetail::where('company_id', $id)->delete();

        $notification = array(
            'message' => 'Company Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    // credit compnay method
    public function CreditCustomer()
    {
        $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->where('due_amount', '!=', '0')->get();
        return view('admin.customer_page.credit_customer', compact('allData'));
    }


    public function EditCreditCustomerInvoice($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        $allBank = Bank::OrderBy('name', 'asc')->get();
        return view('admin.customer_page.edit_customer_invoice', compact('payment', 'allBank'));
    }

    public function UpdateCustomerInvoice(Request $request, $invoice_id)
    {
        if ($request->new_paid_amount < $request->paid_amount) {
            $notification = array(
                'message' => 'Sorry, You Paid maximum amount!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        } else {
            $payment = Payment::where('invoice_id', $invoice_id)->first();
            $payment_details = new PaymentDetail();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_source == 'bank') {
                $bank_name = $request->name;
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



            // account details
            $account_details = new AccountDetail();
            $account_details->customer_id = $payment->customer_id;
            $account_details->invoice_id = $invoice_id;
            $account_details->paid_status = $request->paid_status;
            $account_details->paid_source = $request->paid_source;
            $account_details->bank_name = $bank_name;
            $account_details->status = '0';
            $account_details->note = $note;
            $account_details->date = date('Y-m-d', strtotime($request->date));



            if ($request->paid_status == 'full_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->new_paid_amount;
                $payment->due_amount = '0';
                $payment_details->current_paid_amount = $request->new_paid_amount;

                $account_details->paid_amount = $request->new_paid_amount;
                $account_details->due_amount = '0';
            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = Payment::where('invoice_id', $invoice_id)->first()['paid_amount'] + $request->paid_amount;
                $payment->due_amount = Payment::where('invoice_id', $invoice_id)->first()['due_amount'] - $request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;

                $account_details->paid_amount = $request->paid_amount;
                $account_details->due_amount = $payment->due_amount;
            }

            $payment->save();
            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d', strtotime($request->date));
            $payment_details->paid_status = $request->paid_status;
            $payment_details->paid_source = $request->paid_source;
            $payment_details->bank_name = $bank_name;
            $payment_details->note = $note;

            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();
            $account_details->save();

            $notification = array(
                'message' => 'Payment Updated Successfully!',
                'alert_type' => 'success',
            );
            return redirect()->route('credit.customer')->with($notification);
        }
    }


    public function CustomerInvoiceDetails($invoice_id)
    {
        $payment = Payment::where('invoice_id', $invoice_id)->first();
        // dd($payment);
        return view('admin.pdf.invoice_details_pdf', compact('payment'));
    }


    public function CustomerBill($id)
    {
        $allData = Invoice::orderBy('date', 'desc')->orderBy('invoice_no', 'desc')->where('customer_id', $id)->get();
        return view('admin.customer_page.customer_invoice', compact('allData', 'id'));
    }

    public function CustomerAccountDetails($id)
    {
        $accountDetails = AccountDetail::orderBy('date', 'asc')->where('customer_id', $id)->get();
        $customerInfo = Customer::where('id', $id)->first();
        return view('admin.customer_page.customer_account_details', compact('accountDetails', 'customerInfo'));
    }

    public function CustomerAccountDetailReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $customer_id = $request->customer_id;

        if ($start_date == null && $end_date == null) {
            $billDetails = AccountDetail::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $billDetails = AccountDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])->where('customer_id', $request->customer_id)
                ->get();
        }
        return view('admin.report.customer_account_detials_report', compact('billDetails', 'start_date', 'end_date', 'customer_id'));
    }

    public function GetCustomer($id)
    {
        $customers = Customer::OrderBy('name', 'asc')->where('status', $id)->get();
        return response()->json($customers);
    }

    public function DynamicQueryCustomer()
    {
        // $payment = Payment::get();
        // dd($payment->sum('total_amount'), $payment->sum('paid_amount'), $payment->sum('due_amount'));
        $companies = Customer::get();

        foreach ($companies as $company) {
            $billDetails = AccountDetail::where('customer_id', $company->id)->orderBy('id')->get();
            $previousBalance = 0;

            foreach ($billDetails as $key => $item) {
                $item->balance = $previousBalance + $item->total_amount - $item->paid_amount;
                $item->update();
                $previousBalance = $item->balance;
            }
        }
        dd('success');
    }



    public function CustomerPreviousDue($id) {

        $accountDetail = AccountDetail::where('customer_id', $id)->latest()->first();

        return response()->json($accountDetail);
    }
}
