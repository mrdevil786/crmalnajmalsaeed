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

        $totalVatAmount = $this->getTotalVatAmount();
        $totalIncome = $this->getTotalIncome();

        $productPercentageChange = $this->getPercentageChange(Product::class);
        $customerPercentageChange = $this->getPercentageChange(Customer::class);
        $vatPercentageChange = $this->getAmountPercentageChange('vat_amount');
        $incomePercentageChange = $this->getAmountPercentageChange('subtotal');

        $previousThreeMonthDateRange = $this->getPreviousThreeMonthDateRange();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'productPercentageChange',
            'customerPercentageChange',
            'totalVatAmount',
            'totalIncome',
            'vatPercentageChange',
            'incomePercentageChange',
            'previousThreeMonthDateRange'
        ));
    }

    private function getTotalVatAmount()
    {
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        return Invoice::where('type', 'invoice')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('vat_amount');
    }

    private function getTotalIncome()
    {
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        return Invoice::where('type', 'invoice')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('subtotal');
    }

    private function getPercentageChange($model)
    {
        $currentMonthCount = $model::whereBetween('created_at', $this->getLastThreeMonthsDateRange('current'))->count();
        $previousMonthCount = $model::whereBetween('created_at', $this->getLastThreeMonthsDateRange('previous'))->count();

        if ($previousMonthCount > 0) {
            return (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
        }

        return 0;
    }

    private function getAmountPercentageChange($column)
    {
        $currentMonthAmount = $this->getTotalAmount($column, 'current');
        $previousMonthAmount = $this->getTotalAmount($column, 'previous');

        if ($previousMonthAmount > 0) {
            return (($currentMonthAmount - $previousMonthAmount) / $previousMonthAmount) * 100;
        }

        return 0;
    }

    private function getTotalAmount($column, $period = 'current')
    {
        $dateRange = $this->getLastThreeMonthsDateRange($period);

        return Invoice::where('type', 'invoice')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum($column);
    }

    private function getLastThreeMonthsDateRange($period = 'current')
    {
        $currentMonth = Carbon::now();

        if ($period === 'current') {
            $startDate = $currentMonth->subMonths(3)->startOfMonth();
            $endDate = $currentMonth->subMonth()->endOfMonth();
        } else {
            $startDate = $currentMonth->subMonths(6)->startOfMonth();
            $endDate = $currentMonth->subMonths(4)->endOfMonth();
        }

        return [
            'start' => $startDate,
            'end' => $endDate,
        ];
    }

    private function getPreviousThreeMonthDateRange()
    {
        $startMonth = Carbon::now()->subMonths(3)->format('M');
        $endMonth = Carbon::now()->subMonth()->format('M');

        return "From $startMonth - $endMonth";
    }
}
