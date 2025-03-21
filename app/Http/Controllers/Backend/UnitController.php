<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function UnitAll()
    {
        $unitAll = Unit::latest()->get();
        return view('admin.unit.unit_all', compact('unitAll'));
    } //end method

    public function UnitAdd()
    {

        return view('admin.unit.unit_add');
    } //end method

    public function UnitStore(Request $request)
    {
        Unit::insert([
            'name' => $request->name,
            'short_form' => $request->short_form,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message' => 'Unit  Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('unit.all')->with($notification);
    }

    public function UnitEdit($id)
    {
        $unitInfo = Unit::findOrFail($id);
        return view('admin.unit.unit_edit', compact('unitInfo'));
    }

    public function UnitUpdate(Request $request)
    {
        $unitId  =  $request->id;
        Unit::findOrFail($unitId)->update([
            'name' => $request->name,
            'short_form' => $request->short_form,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Unit Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('unit.all')->with($notification);
    } //end method

    public function UnitDelete($id)
    {
        Unit::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Unit Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }
}
