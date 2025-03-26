<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountDetail;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\OverTime;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\WastesSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\AcceptHeader;

class AccountController extends Controller
{

    /*######### start expense method  #################**/
    public function AllExpense()
    {
        $allExpense = Expense::latest()->get();
        return view('admin.accounts.expense_page.all_expense', compact('allExpense'));
    }
    public function AddExpense()
    {
        $employees = Employee::all();
        return view('admin.accounts.expense_page.add_expense', compact('employees'));
    }

    public function StoreExpense(Request $request)
    {
        $expense = new Expense();
        if ($request->expense_head == 'Other') {
            $expense->head = $request->others;
        } else {
            $expense->head = $request->expense_head;
        }
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->description = $request->description;
        $expense->created_at = Carbon::now();
        $expense->save();

        $notification = array(
            'message' => 'Expense Addedd Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }


    public function EditExpense($id)
    {
        $expenseInfo = Expense::findOrFail($id);
        return view('admin.accounts.expense_page.edit_expense', compact('expenseInfo'));
    }

    public function UpdateExpense(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        if ($request->expense_head == 'Other') {
            $expense->head = $request->others;
        } else {
            $expense->head = $request->expense_head;
        }


        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->save();
        $notification = array(
            'message' => 'Expense updated Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }

    public function MonthlyExpense()
    {
        $current_month = date('m');
        $monthlyExpense = Expense::whereMonth('created_at', $current_month)->get();

        $totalMonthlyExpense = Expense::whereMonth('created_at', $current_month)->sum('amount');
        return view('admin.accounts.expense_page.monthly_expense', compact('monthlyExpense', 'totalMonthlyExpense'));
    }

    public function DailyExpense()
    {
        $today = date('Y-m-d');
        $todayExpense = Expense::whereDate('created_at', $today)->get();
        $totalDailyExpense = Expense::whereDate('created_at', $today)->sum('amount');
        return view('admin.accounts.expense_page.daily_expense', compact('todayExpense', 'totalDailyExpense'));
    }


    public function YearlyExpense()
    {
        $current_year = date('Y');
        $yearlyExpense = Expense::whereYear('created_at', $current_year)->get();
        $totalYearlyExpense = Expense::whereYear('created_at', $current_year)->sum('amount');
        return view('admin.accounts.expense_page.yearly_expense', compact('yearlyExpense', 'totalYearlyExpense'));
    }


    public function GetExpense(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;


        if ($start_date == null && $end_date == null) {
            $allExpense = Expense::paginate(2);
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allExpense = Expense::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.accounts.expense_page.search_expense_result', compact('allExpense', 'start_date', 'end_date',));
    }

    public function DeleteExpense($id)
    {
        Expense::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Expense Deleted Successfully',
            'alert_type' => 'success'
        );

        return redirect()->route('all.expense')->with($notification);
    }

    /*######### End All expense method  #################**/


    /*######### Start Prodile Calculate method  #################**/
    public function AddProfit()
    {
        // $purchaseData = Purchase::get();
        // $expenseData = Expense::get();
        // return view('admin.accounts.profit.add_profit', compact('purchaseData','expenseData'));
        return view('admin.accounts.profit.add_profit');
    }


    public function GetProfit(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $expense = Expense::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
            $purchase = Purchase::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
            $payment = Payment::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        $total_sale = $payment->sum('paid_amount');
        $total_purchase = $purchase->sum('purchase_amount');
        $total_expense = $expense->sum('amount');
        $profit =  $total_sale($total_purchase + $total_expense);
        return view('admin.accounts.profit.result', compact('profit', 'startDate', 'endDate'));
    }


    public function GetAccountDetails(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $company_id = $request->company_id;

        if ($start_date == null && $end_date == null) {
            $billDetails = AccountDetail::all();
        }

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $billDetails = AccountDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])->where('company_id', $request->company_id)
                ->get();
        }
        return view('admin.report.account_detials_report', compact('billDetails', 'start_date', 'end_date', 'company_id'));
    }


     // opening balance method
     public function AllOpeningBalance()
     {
         $allOpening = AccountDetail::where('status', '2')->get();
         return view('admin.wholesaler.opening_balance.all_opening', compact('allOpening'));
     }
     public function AddOpeningBalance()
     {
         $customers = Customer::OrderBy('name', 'asc')->where('status', '1')->get();
         return view('admin.wholesaler.opening_balance.add_opening', compact('customers'));
     }
 
     public function StoreOpeningBalance(Request $request)
     {
         DB::beginTransaction();
         try{
 
             $exitingWholesaler = AccountDetail::where('customer_id', $request->customer_id)->where('status', '2')->first();
         if ($exitingWholesaler) {
             $notification = array(
                 'message' => 'Opening Balance Already Added!',
                 'alert-type' => 'error',
             );
             return redirect()->back()->with($notification);
         } else {
             $latestAccount = AccountDetail::where('customer_id', $request->customer_id)->latest('id')->first();
             $account_details = new AccountDetail();
             $account_details->total_amount = $request->total_amount;
             $account_details->paid_amount = 0;
             $account_details->due_amount = $request->total_amount;
             $account_details->balance = $latestAccount->balance + $request->total_amount;
             $account_details->customer_id = $request->customer_id;
             $account_details->date = date('Y-m-d', strtotime($request->date));
             $account_details->status = '2';
             $account_details->save();
 
             DB::commit();
 
             $notification = array(
                 'message' => 'Opening Balance Added Successfully!',
                 'alert_type' => 'success',
             );
             return redirect()->route('all.opening.balance')->with($notification);
         }
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('Storing Opening Balance Error' . $e->getMessage() . 'Line: ' . $e->getLine());
 
             $notification = array(
                 'message' => 'Opening Balance Not Added!',
                 'alert-type' => 'error',
             );
             return redirect()->back()->with($notification);
         }
     }
 
 
     public function EditOpeningBalance($id)
     {
         $accountInfo = AccountDetail::findOrFail($id);
         $customers = Customer::where('status', '1')->OrderBy('name', 'asc')->get();
         return view('admin.wholesaler.opening_balance.edit_opening', compact('accountInfo', 'customers'));
     }
 
     public function UpdateOpeningBalance(Request $request)
     {

        DB::beginTransaction();
        try{
            $accountId = $request->id;

            $previousAccount = AccountDetail::where('id', '<' , $accountId)->where('customer_id', $request->customer_id)->latest('id')->first();

            $previousBalance = $previousAccount->balance;
    
            AccountDetail::findOrFail($accountId)->update([
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'customer_id' => $request->customer_id,
                'due_amount' => $request->total_amount - $request->paid_amount,
                'balance' => $previousBalance + ($request->total_amount - $request->paid_amount),
                'date' => date('Y-m-d', strtotime($request->date)),
            ]);

            $this->resetAccountBalance(AccountDetail::findOrFail($accountId));


            DB::commit();

            $notification = array(
                'message' => 'Opening Balance Updated Successfully',
                'alert_type' => 'success'
            );
    
            return redirect()->route('all.opening.balance')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Storing Opening Balance Error' . $e->getMessage() . 'Line: ' . $e->getLine());

            $notification = array(
                'message' => 'Opening Balance Not Added!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);
        }
 
         
     }

     private function resetAccountBalance($accountID)
    {        
        $accountDetail = AccountDetail::where('id', $accountID->id)
            ->first();
        
        if ($accountDetail) {
            $nextAccountDetails = AccountDetail::where('id', '>', $accountDetail->id)
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
 
 
     public function DeleteOpeningBalance($id)
     {
        DB::beginTransaction();
        try {
            $accountDetails = AccountDetail::findOrFail($id);
            AccountDetail::where('id', '>', $accountDetails->id)->decrement('balance', $accountDetails->balance);
            
            AccountDetail::findOrFail($id)->delete();

            DB::commit();

            $notification = array(
                'message' => 'Balance Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.opening.balance')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Storing Opening Balance Error' . $e->getMessage() . 'Line: ' . $e->getLine());

            $notification = array(
                'message' => 'Opening Balance Not Delete!',
                'alert-type' => 'error',
            );
            return redirect()->back()->with($notification);

        }
     }
 
     // bill wise opeinig balance  added
}
