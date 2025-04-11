<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advanced;
use App\Models\Bonus;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeSalaryLog;
use App\Models\OverTime;
use App\Models\PaySalary;
use App\Models\PaySalaryDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{
    public function EmployeeAll()
    {
        $allData = Employee::all();
        return view('admin.employee_page.all_employee', compact('allData'));
    }
    public function EmployeeAdd()
    {
        return view('admin.employee_page.add_employee');
    }

    public function EmployeeStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::orderBy('id', 'desc')->first();
            if ($employee == null) {
                $firstReg = '0';
                $employeeId = $firstReg + 1;
            } else {
                $employee = Employee::orderBy('id', 'desc')->first()->id;
                $employeeId = $employee + 1;
            }

            if ($employeeId < 10) {
                $id_no = '000' . $employeeId; //0009
            } elseif ($employeeId < 100) {
                $id_no = '00' . $employeeId; //0099
            } elseif ($employeeId < 1000) {
                $id_no = '0' . $employeeId; //0999
                $id_no = '0' . $employeeId; //0999
            }

            $check_year = date('Y');

            $name = $request->name;
            $words = explode(' ', $name);
            $acronym = '';
            foreach ($words as $w) {
                $acronym .= mb_substr($w, 0, 1);
            }

            $employee_id = $acronym . '-' . $check_year . '.' . $id_no;



            if ($request->file('image')) {
                $image = $request->file('image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $save_url = 'upload/employee_image/' . $name_gen;
                Image::make($image)->resize(200, 200)->save(public_path($save_url));


                $employeeInfo = new Employee();
                $employeeInfo->name = $request->name;
                $employeeInfo->employee_id = $employee_id;
                $employeeInfo->email = $request->email;
                $employeeInfo->phone = $request->phone;
                $employeeInfo->designation = $request->designation;
                $employeeInfo->salary = $request->salary;
                $employeeInfo->address = $request->address;
                $employeeInfo->nid = $request->nid;
                $employeeInfo->joining_date = date('Y-m-d', strtotime($request->joining_date));
                $employeeInfo->image =  $save_url;
                $employeeInfo->created_at =  Carbon::now();
                $employeeInfo->save();

                $employee_salary = new EmployeeSalaryLog();
                $employee_salary->emp_id = $employeeInfo->id;
                $employee_salary->present_salary = $request->salary;
                $employee_salary->previous_salary = $request->salary;
                $employee_salary->increment_salary = '0';
                $employee_salary->effected_salary = date('Y-m-d', strtotime($request->joining_date));
                $employee_salary->save();


                $notification = [
                    'message' => 'Employee Added Successfully',
                    'alert-type' => 'success',
                ];
            } else {
                $employeeInfo = new Employee();
                $employeeInfo->name = $request->name;
                $employeeInfo->employee_id = $employee_id;
                $employeeInfo->email = $request->email;
                $employeeInfo->phone = $request->phone;
                $employeeInfo->designation = $request->designation;
                $employeeInfo->salary = $request->salary;
                $employeeInfo->joining_date = date('Y-m-d', strtotime($request->joining_date));
                $employeeInfo->created_at =  Carbon::now();
                $employeeInfo->save();

                $employee_salary = new EmployeeSalaryLog();
                $employee_salary->emp_id = $employeeInfo->id;
                $employee_salary->present_salary = $request->salary;
                $employee_salary->previous_salary = $request->salary;
                $employee_salary->increment_salary = '0';
                $employee_salary->effected_salary = date('Y-m-d', strtotime($request->joining_date));
                $employee_salary->save();

                $notification = [
                    'message' => 'Employee Added Successfully Without Image',
                    'alert-type' => 'success'
                ];
            }
            DB::commit();
            return redirect()->route('all.employee')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error : creating employee ' . $e->getMessage() . 'Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    } //end method


    public function EmployeeEdit($id)
    {
        $employeeInfo = Employee::findOrFail($id);
        return view('admin.employee_page.edit_employee', compact('employeeInfo'));
    }

    public function EmployeeUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeeId = $request->id;

            if ($request->file('image')) {
                $image = $request->file('image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                $existing_image = Employee::findOrFail($employeeId);
                @unlink($existing_image->image);

                $save_url = 'upload/employee_image/' . $name_gen;
                Image::make($image)->resize(200, 200)->save(public_path($save_url));


                Employee::findOrFail($employeeId)->update([
                    'name' => $request->name,
                    'employee_id' => $request->employee_id,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'designation' => $request->designation,
                    'salary' => $request->salary,
                    'joining_date' => $request->joining_date,
                    'image' => $save_url,
                ]);
                $notification = [
                    'message' => 'Employee Updated Successfully',
                    'alert_type' => 'success'
                ];
            } else {
                Employee::findOrFail($employeeId)->update([
                    'name' => $request->name,
                    'employee_id' => $request->employee_id,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'designation' => $request->designation,
                    'salary' => $request->salary,
                    'joining_date' => $request->joining_date,
                ]);
                $notification = [
                    'message' => 'Employee Updated Successfully without image',
                    'alert_type' => 'success'
                ];
            }

            DB::commit();
            return redirect()->route('all.employee')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error : creating employee ' . $e->getMessage() . 'Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function EmployeeView($id)
    {
        $employeeInfo = Employee::findOrFail($id);
        return view('admin.employee_page.view_employee', compact('employeeInfo'));
    }
    public function EmployeeDelete($id)
    {
        DB::beginTransaction();
        try {
            Employee::findOrFail($id)->delete();
            EmployeeSalaryLog::where('emp_id', $id)->delete();
            PaySalary::where('employee_id', $id)->delete();
            PaySalaryDetail::where('employee_id', $id)->delete();
            Advanced::where('employee_id', $id)->delete();
            Bonus::where('employee_id', $id)->delete();
            OverTime::where('employee_id', $id)->delete();

            $notification = [
                'message' => 'Employee Deleted Successfully',
                'alert-type' => 'success'
            ];
            DB::commit();
            return redirect()->route('all.employee')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error : deleting employee ' . $e->getMessage() . 'Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }


    public function SalaryIncrement($id)
    {
        $allData = Employee::findOrFail($id);
        return view('admin.employee_page.salary.employee_salary_increment', compact('allData'));
    } //end method

    public function SalaryIncrementUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $previous_salary = $employee->salary;
        $present_salary = (float)$previous_salary + $request->increment_salary;
        $employee->salary = $present_salary;
        $employee->save();

        $salaryData = new EmployeeSalaryLog();
        $salaryData->emp_id = $id;
        $salaryData->previous_salary = $previous_salary;
        $salaryData->present_salary = $present_salary;
        $salaryData->increment_salary = $request->increment_salary;
        $salaryData->effected_salary = date('Y-m-d', strtotime($request->effected_salary));
        $salaryData->save();

        $notification = array(
            'message' => 'Employee Salary Increment Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('employee.salary.details', $id)->with($notification);
    } //end method

    public function SalaryDetails($id)
    {
        $employee = Employee::findOrFail($id);
        $salaryData = EmployeeSalaryLog::where('emp_id', $employee->id)->get();
        return view('admin.employee_page.salary.employee_salary_details', compact('salaryData', 'employee'));
    } //end method
}
