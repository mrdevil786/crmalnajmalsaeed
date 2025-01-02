<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        
        // Keep the same variable name but now fetch VAT for the last 3 months
        $totalVatAmount = $this->getTotalVatAmount();  // For the last 3 months
        $totalIncome = $this->getTotalIncome();  // For the current quarter

        $productPercentageChange = $this->getQuarterlyPercentageChange(Product::class);
        $customerPercentageChange = $this->getQuarterlyPercentageChange(Customer::class);
        $vatPercentageChange = $this->getQuarterlyAmountPercentageChange('vat_amount');
        $incomePercentageChange = $this->getQuarterlyAmountPercentageChange('subtotal');

        $currentQuarterStartMonth = $this->getQuarterStartMonth('current');
        $previousQuarterStartMonth = $this->getQuarterStartMonth('previous');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'productPercentageChange',
            'customerPercentageChange',
            'totalVatAmount',
            'totalIncome',
            'vatPercentageChange',
            'incomePercentageChange',
            'currentQuarterStartMonth',
            'previousQuarterStartMonth'
        ));
    }

    // Modify the method to calculate VAT for the last 3 months
    private function getTotalVatAmount()
    {
        // Get the date range for the last 3 months
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();  // End of the previous month

        return Invoice::where('type', 'invoice')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('vat_amount');
    }

    private function getQuarterStartMonth($quarter = 'current')
    {
        $quarterStartMonths = [
            1 => 'January', 2 => 'April', 3 => 'July', 4 => 'October'
        ];
        $quarterNumber = Carbon::now()->quarter;

        if ($quarter === 'previous') {
            $quarterNumber = $quarterNumber === 1 ? 4 : $quarterNumber - 1;
        }

        return $quarterStartMonths[$quarterNumber];
    }

    private function getQuarterlyPercentageChange($model)
    {
        $currentQuarterCount = $model::whereBetween('created_at', $this->getQuarterDateRange('current'))->count();
        $previousQuarterCount = $model::whereBetween('created_at', $this->getQuarterDateRange('previous'))->count();

        if ($previousQuarterCount > 0) {
            return (($currentQuarterCount - $previousQuarterCount) / $previousQuarterCount) * 100;
        }

        return 0;
    }

    private function getQuarterlyAmountPercentageChange($column)
    {
        $currentQuarterAmount = $this->getTotalAmount($column, 'current');
        $previousQuarterAmount = $this->getTotalAmount($column, 'previous');

        if ($previousQuarterAmount > 0) {
            return (($currentQuarterAmount - $previousQuarterAmount) / $previousQuarterAmount) * 100;
        }

        return 0;
    }

    private function getTotalAmount($column, $quarter = 'current')
    {
        $dateRange = $this->getQuarterDateRange($quarter);

        return Invoice::where('type', 'invoice')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum($column);
    }

    private function getQuarterDateRange($quarter = 'current')
    {
        $currentMonth = now()->month;

        if ($quarter === 'current') {
            if ($currentMonth >= 1 && $currentMonth <= 3) {
                return [
                    'start' => now()->startOfYear(),
                    'end' => Carbon::create(now()->year, 3, 31),
                ];
            } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
                return [
                    'start' => now()->startOfQuarter(2),
                    'end' => now()->endOfQuarter(2),
                ];
            } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
                return [
                    'start' => now()->startOfQuarter(3),
                    'end' => now()->endOfQuarter(3),
                ];
            } else {
                return [
                    'start' => now()->startOfQuarter(4),
                    'end' => now()->endOfQuarter(4),
                ];
            }
        } else {
            if ($currentMonth >= 1 && $currentMonth <= 3) {
                return [
                    'start' => now()->subQuarter()->startOfQuarter(),
                    'end' => Carbon::create(now()->year - 1, 12, 31),
                ];
            } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
                return [
                    'start' => now()->subQuarter()->startOfQuarter(),
                    'end' => now()->subQuarter()->endOfQuarter(),
                ];
            } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
                return [
                    'start' => now()->subQuarter()->startOfQuarter(),
                    'end' => now()->subQuarter()->endOfQuarter(),
                ];
            } else {
                return [
                    'start' => now()->subQuarter()->startOfQuarter(),
                    'end' => now()->subQuarter()->endOfQuarter(),
                ];
            }
        }
    }

    private function getTotalIncome()
    {
        return $this->getTotalAmount('subtotal');
    }
}
