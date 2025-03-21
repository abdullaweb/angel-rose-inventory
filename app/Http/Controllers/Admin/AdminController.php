<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Purchase;
use App\Models\User;
use App\Models\WastesSale;
use App\Models\Customer;
use App\Models\SalesProfit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDO;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $purchase = Purchase::get();
        $expense = Expense::get();
        $invoices = Invoice::where('status', '1')->where('return_status', '0')->pluck('id')->toArray();
        $payment = Payment::whereIn('invoice_id', $invoices)->get();
        $dueAmount = $payment->sum('due_amount');
        $productSale = Payment::sum('total_amount');
        $products = Product::get();
        // $runningMonthSale = Payment::whereMonth('created_at', date('m'))->sum('total_amount');
        $runningMonthExpense = Expense::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $runningMonthSale = InvoiceDetail::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('selling_price');
        $runningMonthPurchase = Purchase::whereMonth('date', date('m')) ->whereYear('date', date('Y'))->sum('total_amount');
        
        $runningMonthWholeSale = SalesProfit::with('invoice')->whereMonth('date', date('m'))->whereYear('date', date('Y'))->whereHas('invoice', function ($query) {
            $query->whereHas('customer', function ($query) {
                $query->where('status', '1')->where('return_status', '0');
            });
        })->sum('profit');

        $runningMonthRetail = SalesProfit::with('invoice')->whereMonth('date', date('m'))->whereYear('date', date('Y'))->whereHas('invoice', function ($query) {
            $query->whereHas('customer', function ($query) {
                $query->where('status', '0')->where('return_status', '0');
            });
        })->sum('profit');
        
        $customers = Customer::get();
        $retailCustomerAmount = 0;
        $wholeSaleCustomerAmount = 0;
        foreach ($customers as $customer) {
            if ($customer->status == '0') {
                $invoice = Invoice::where('return_status', '0')->whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('customer_id', $customer->id)->get();
                foreach ($invoice as $item) {
                    $retailCustomerAmount += InvoiceDetail::where('invoice_id', $item->id)->sum('selling_price');
                }
            } else {
                $invoice = Invoice::where('return_status', '0')->whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('customer_id', $customer->id)->get();
                foreach ($invoice as $item) {
                    $wholeSaleCustomerAmount += InvoiceDetail::where('invoice_id', $item->id)->sum('selling_price');
                }
            }
        }
        return view('admin.index', compact('purchase', 'expense', 'dueAmount', 'runningMonthSale','products','retailCustomerAmount','wholeSaleCustomerAmount','runningMonthPurchase', 'runningMonthWholeSale', 'runningMonthRetail','runningMonthExpense'));
    }

    public function RedirectDashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login');
    }


    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    } //end method

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(('upload/admin_images'), $fileName);
            $data['photo'] = $fileName;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //end method

    public function ChangeAdminPassword()
    {
        return view('admin.admin_change_password');
    } //end method


    public function UpdateAdminPassword(Request $request)
    {
        // validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password'
        ]);


        // match the old password
        $hashedPassword = auth::user()->password;
        if (!Hash::check($request->old_password, $hashedPassword)) {
            return back()->with('error', "Old Password Doesn't Match!");
        } else {
            // update the new password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            return back()->with('status', 'Password Change Successfully');
        }
    }





    // all admin method
    public function AdminAll()
    {
        $allAdmin = User::where('role', 'admin')->latest()->get();
        return view('admin.admin_all.all_admin', compact('allAdmin'));
    }
    public function AdminAdd()
    {
        $roles = Role::all();
        return view('admin.admin_all.add_admin', compact('roles'));
    } //end method

    public function AdminStore(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 'admin';
        $user->status = 'active';
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->role) {
            $user->syncRoles($request->role);
        }

        $notification = array(
            'message' => 'New Admin  Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    } //end method

    public function EditAdminRole($id)
    {
        $adminInfo = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.admin_all.edit_admin', compact('adminInfo', 'roles'));
    } //end method

    public  function UpdateAdminRole(Request $request)
    {
        $adminId = $request->id;

        $user = User::findOrFail($adminId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 'admin';
        $user->status = 'active';
        $user->address = $request->address;
        $user->save();

        $user->roles()->detach();
        if ($request->role) {
            $user->syncRoles($request->role);
        }

        $notification = array(
            'message' => 'New Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    } //end method

    public function DeleteAdminRole($id)
    {
        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }
        $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    }
}
