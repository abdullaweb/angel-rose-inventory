<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advanced;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\OverTime;
use App\Models\PaySalaryDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function GetEmployeeSalary(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);

        $advanced_amount = Advanced::where('employee_id', $request->employee_id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', date('Y'))
            ->first();
        $overtime = OverTime::where('employee_id', $request->employee_id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', date('Y'))
            ->first();
        $bonus = Bonus::where('employee_id', $request->employee_id)
            ->where('month', date('F', strtotime('-1 month')))
            ->where('year', date('Y'))
            ->first();

        $pay_salary = PaySalaryDetail::where('employee_id', $request->employee_id)
            ->where('paid_month', date('F', strtotime('-1 month')))
            ->where('paid_year', date('Y'))
            ->get();

        // check total salary
        if ($pay_salary->isEmpty()) {
            if ($employee != null && $overtime != null && $bonus != null) {
                $total_salary = $employee->salary + $overtime->ot_amount +  $bonus->bonus_amount;
            } elseif ($employee != null && $overtime != null && $bonus == null) {
                $total_salary = $employee->salary + $overtime->ot_amount;
            } elseif ($employee != null && $overtime == null && $bonus != null) {
                $total_salary = $employee->salary + $bonus->bonus_amount;
            } elseif ($employee != null && $overtime == null && $bonus == null) {
                $total_salary = $employee->salary;
            }
        } else {
            if ($employee != null && $overtime != null && $bonus != null) {
                $total_salary = $employee->salary + $overtime->ot_amount +  $bonus->bonus_amount - $pay_salary->sum('paid_amount');
            } elseif ($employee != null && $overtime != null && $bonus == null) {
                $total_salary = $employee->salary + $overtime->ot_amount - $pay_salary->sum('paid_amount');
            } elseif ($employee != null && $overtime == null && $bonus != null) {
                $total_salary = $employee->salary + $bonus->bonus_amount - $pay_salary->sum('paid_amount');
            } elseif ($employee != null && $overtime == null && $bonus == null) {
                $total_salary = $employee->salary - $pay_salary->sum('paid_amount');
            }
        }
        if ($advanced_amount != NULL) {
            $total_salary -= $advanced_amount->advance_amount;
        }
        return response()->json($total_salary);
    }


    // public function GetEmployeeAdvance(Request $request)
    // {
    //     $advanced = Advanced::where('employee_id', $request->employee_id)->get();
    //     if ($advanced->isEmpty()) {
    //         $advanced_amount = 0;
    //     } else {
    //         $advanced_amount = $advanced->sum('advance_amount');
    //     }
    //     return response()->json($advanced_amount);
    // }

    // public function GetProduct(Request $request)
    // {
    //     $prouducts = Product::where('category_id', $request->category_id)->get();
    //     // dd($prouducts);

    //     return response()->json($prouducts);
    // }


}
