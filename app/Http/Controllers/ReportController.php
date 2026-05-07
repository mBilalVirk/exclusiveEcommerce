<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
       // $this->middleware(['auth', 'admin']);
    }

    /**
     * Show reports dashboard
     */
    public function index()
    {
        $salesSummary = $this->exportService->getSalesSummary(
            now()->subDays(30),
            now()
        );

        return view('admin.exports.index', compact('salesSummary'));
    }

    /**
     * Download orders CSV
     */
    public function downloadOrdersCSV(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ];

            $csv = $this->exportService->exportOrdersCSV($filters);

            Log::info('Orders CSV exported by admin: ' . auth()->user()->name);

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="orders_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export orders');
        }
    }

    /**
     * Download orders PDF
     */
    public function downloadOrdersPDF(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ];

            $pdf = $this->exportService->exportOrdersPDF($filters);

            Log::info('Orders PDF exported by admin: ' . auth()->user()->name);

            return $pdf->download('orders_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export orders');
        }
    }

    /**
     * Download customers CSV
     */
    public function downloadCustomersCSV(Request $request)
    {
        try {
            $filters = [
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ];

            $csv = $this->exportService->exportCustomersCSV($filters);

            Log::info('Customers CSV exported by admin: ' . auth()->user()->name);

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="customers_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export customers');
        }
    }

    /**
     * Download customers PDF
     */
    public function downloadCustomersPDF(Request $request)
    {
        try {
            $filters = [
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
            ];

            $pdf = $this->exportService->exportCustomersPDF($filters);

            Log::info('Customers PDF exported by admin: ' . auth()->user()->name);

            return $pdf->download('customers_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export customers');
        }
    }

    /**
     * Download products CSV
     */
    public function downloadProductsCSV(Request $request)
    {
        try {
            $filters = [
                'category' => $request->get('category'),
                'in_stock_only' => $request->get('in_stock_only'),
            ];

            $csv = $this->exportService->exportProductsCSV($filters);

            Log::info('Products CSV exported by admin: ' . auth()->user()->name);

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="products_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export products');
        }
    }

    /**
     * Download revenue report PDF
     */
    public function downloadRevenueReport(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        try {
            $pdf = $this->exportService->exportRevenueReportPDF(
                $validated['date_from'],
                $validated['date_to']
            );

            Log::info('Revenue report PDF exported by admin: ' . auth()->user()->name);

            return $pdf->download('revenue_report_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export revenue report');
        }
    }

    /**
     * Download tax report PDF
     */
    public function downloadTaxReport(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        try {
            $pdf = $this->exportService->exportTaxReportPDF(
                $validated['date_from'],
                $validated['date_to']
            );

            Log::info('Tax report PDF exported by admin: ' . auth()->user()->name);

            return $pdf->download('tax_report_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export tax report');
        }
    }
}