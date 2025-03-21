<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Purchase;
use App\Models\PurchaseMeta;
use App\Models\SalesProfit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function CategoryReport()
    {
        $categories = Category::all();
        $invoice = Invoice::all();
        return view('admin.report.category_wise_report', compact('categories', 'invoice'));
    }


    public function GetCategoryReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $category_id = $request->category_id;
        $report_type = $request->report_type;
        $categories = Category::all();
        
        
        if ($start_date && $end_date && $category_id && $report_type) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            if ($report_type == 'purchase') {
                $allSearchResult = PurchaseMeta::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                    ->where('category_id', $category_id)
                    ->get();
            } elseif ($report_type == 'sales') {
                $allSearchResult = InvoiceDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                    ->where('category_id', $category_id)
                    ->get();
            }

            // dd($allSearchResult);

        }

        return view('admin.report.category_wise_report_result', compact('categories', 'category_id', 'allSearchResult', 'start_date', 'end_date', 'report_type','$payment'));
    }

    public function GetCategoryReportSummary()
    {
        $categories = Category::all();
        return view('admin.report.category_wise_report_summary', compact('categories'));
    }

    public function PrintCategorySummary(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $categories = Category::all();
        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $allSearchResult = InvoiceDetail::whereBetween('created_at', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.category_wise_report_summary_print', compact('categories', 'allSearchResult', 'start_date', 'end_date',));
    }


    // invoice report all method
    public function DailyInvoiceReport()
    {
        return view('admin.invoice.daily_invoice_report');
    } //end method

    public function DailyInvoiceReportPdf(Request $request)
    {
        $sdate = date('Y-m-d', strtotime($request->start_date));
        $edate = date('Y-m-d', strtotime($request->end_date));
        $report_head = $request->report_head;
        if ($report_head == 'purchase') {
             $allData = Purchase::whereBetween('date', [$sdate, $edate])->get();
        } else if ($report_head == 'sales') {
            // $allData = Invoice::whereBetween('created_at', [$request->start_date, Carbon::parse($request->end_date)->endOfDay()])->get();
            $allData = Invoice::whereBetween('date', [$sdate, $edate])->get();
        }
        return view('admin.pdf.daily_invoice_report_pdf', compact('allData', 'sdate', 'edate', 'report_head'));
    }
    
    public function ProfitReport()
    {
        $invoices = Invoice::where('status', '1')->where('return_status', '0')->pluck('id')->toArray();

        $totalProfit = SalesProfit::whereIn('invoice_id', $invoices)->sum('profit');
        $totalSales = SalesProfit::whereIn('invoice_id', $invoices)->paginate(20);
        // $totalSales = SalesProfit::whereIn('invoice_id', $invoices)->get();

        // dd($totalSales);
        
        return view('admin.report.profit_report', compact('totalSales', 'totalProfit'));
    }

    public function ProfitResult(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $invoices = Invoice::where('status', '1')->where('return_status', '0')->pluck('id')->toArray();

        if ($start_date && $end_date) {
            $startDate = Carbon::parse($start_date)->toDateTimeString();
            $endDate = Carbon::parse($end_date)->toDateTimeString();
            $totalSales = SalesProfit::whereIn('invoice_id', $invoices)->whereBetween('date', [$start_date, Carbon::parse($end_date)->endOfDay()])
                ->get();
        }

        return view('admin.report.profit_result', compact('totalSales', 'startDate', 'endDate'));
    }
}
