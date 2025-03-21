<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advanced;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvancedController extends Controller
{
    public function AllAdvancedSalary()
    {
        $allAdvanced = Advanced::all();
        return view('admin.salary.advanced_salary.all_advanced', compact('allAdvanced'));
    }

    public function AddAdvancedSalary()
    {
        $employees = Employee::orderBy('name', 'desc')->get();
        return view('admin.salary.advanced_salary.add_advanced', compact('employees'));
    }

    public function EditAdvancedSalary($id)
    {
        $employees = Employee::all();
        $advancedSalary = Advanced::findOrFail($id);
        return view('admin.salary.advanced_salary.edit_advanced', compact('advancedSalary', 'employees'));
    }

    public function StoreAdvancedSalary(Request $request)
    {
        // dd($request->all());

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $monthName = $date->format('F');
        $year = $date->format('Y');

        $advancedSalary = Advanced::where('month', $request->month)->where('employee_id', $request->employee_id)->first();

        if ($advancedSalary === NULL) {
            $advanced = new Advanced();
            $advanced->advance_amount = $request->advanced_amount;
            $advanced->employee_id = $request->employee_id;
            $advanced->date = $request->date;
            $advanced->month = $request->month;
            $advanced->year = $year;
            $advanced->created_at = Carbon::now();
            $advanced->save();

            $notification = array(
                'message' => 'Advanced Salary Added Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.advanced.salary')->with($notification);
        } else {
            $notification = array(
                'message' => 'Advanced Salary Already Paid!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }



    public function UpdateAdvancedSalary(Request $request)
    {



        $advanced_id = $request->id;

        $date = Carbon::createFromFormat('m/d/Y', date('m/d/Y', strtotime($request->date)));
        $year = $date->format('Y');

        Advanced::findOrFail($advanced_id)->update([
            'advance_amount' => $request->advanced_amount,
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $year,
            'date' => $request->date,
        ]);

        $notification = array(
            'message' => 'Advanced Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('all.advanced.salary')->with($notification);
    }

    public function DeleteAdvancedSalary($id)
    {
        Advanced::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Advanced Salary Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.advanced.salary')->with($notification);
    }
}
