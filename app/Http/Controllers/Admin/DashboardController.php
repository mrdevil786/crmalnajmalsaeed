<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        // Get percentage changes
        $customerPercentageChange = $this->getCustomerPercentageChange();
        $productPercentageChange = $this->getProductPercentageChange();

        // Pass all necessary variables to the view
        return view('admin.dashboard', compact('totalProducts', 'totalCustomers', 'productPercentageChange', 'customerPercentageChange'));
    }

    private function getProductPercentageChange()
    {
        $totalProducts = Product::count();
        $productsLastWeek = Product::where('created_at', '>=', now()->subWeek(1))->count();

        $percentageChange = 0;
        if ($productsLastWeek > 0) {
            $percentageChange = (($totalProducts - $productsLastWeek) / $productsLastWeek) * 100;
        }

        return $percentageChange;
    }

    private function getCustomerPercentageChange()
    {
        $totalCustomers = Customer::count();
        $customersLastWeek = Customer::where('created_at', '>=', now()->subWeek(1))->count();

        $percentageChange = 0;
        if ($customersLastWeek > 0) {
            $percentageChange = (($totalCustomers - $customersLastWeek) / $customersLastWeek) * 100;
        }

        return $percentageChange;
    }
}
