<?php

namespace App\Http\Controllers;

use App\Models\AccountDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Exports\CustomerLedgerExport;
use Maatwebsite\Excel\Facades\Excel;


class LedgerController extends Controller
{
     // Show supplier ledger view
     public function CustomerLedger()
     {
         $customers = Customer::all();
         $title = 'Customer Ledger';
         return view('admin.ledger.customer_ledger', compact('customers', 'title'));
     }

     // Fetch supplier ledger via AJAX
     public function CustomerfetchLedger(Request $request)
     {
         $customerId = $request->customer_id;
         $customerInfo = Customer::findOrFail($customerId);
         // $account = Account::where('name', $customerInfo->name)->first();
         if ($customerInfo) {
             $ledger = AccountDetail::where('customer_id', $customerInfo->id)
                 ->get();
             return response()->json(['ledger' => $ledger, 'customer' => $customerInfo]);
         } else {
             return response()->json(['notfound' => null, 'customer' => $customerInfo]);
         }
     }

     // Download the supplier ledger as a PDF
     public function CustomerdownloadLedger(Request $request)
     { {
             $customer = Customer::find($request->customer_id);
             $ledger = AccountDetail::where('customer_id', $request->customer_id)
                 ->get();

             if (!$ledger) {
                 return response()->json(['error' => 'No ledger data found'], 404);
             }

             $pdf = Pdf::loadView('admin.ledger.customer_ledger_pdf', compact('customer', 'ledger'));

             return $pdf->download('admin.ledger.customer_ledger_pdf');
         }

         // Eager load transactions for PDF generation

         // $customerId = $request->customer_id;
         // $customerInfo = Customer::findOrFail($customerId);
         // $ledger = LedgerEntry::where('customer_id', $customerId)
         //     ->with('transaction')
         //     ->get();
         // $customer = customer::find($customerId);

         // $pdf = PDF::loadView('admin.ledger.report.customer.customer_ledger_pdf', compact('ledger', 'customer'));
         // return $pdf->download('customer_ledger_' . $customer->name . '.pdf');
     }

     public function  PdfView()
     {
         return view('admin.ledger.report.supplier.view_test');
     }
}
