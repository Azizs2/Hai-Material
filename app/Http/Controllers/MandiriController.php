<?php

namespace App\Http\Controllers;
use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use Brian2694\Toastr\Facades\Toastr;
use App\Model\BusinessSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\Order;

class MandiriController extends Controller
{
    public function index(Request $request)
    {
        $business_setting = BusinessSetting::first();

        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $tran = Str::random(6) . '-' . rand(1, 1000);
        $order_id = Order::orderBy('id', 'DESC')->first()->id ?? 100001;
        $new_order_id = $order_id + 1;
        // return $new_order_id;

        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $orders = Order::where(['id' => $new_order_id])->first();

        // return $value;
        // return $orders;

        $mandiris = array(
            'company_email' => $company_email,
            'action' => 'pay',
            'amount' => round($value, 2),
            'currency' => Helpers::currency_code(), //USD
            'description' => 'Transaction ID: ' . $tran,
            'order_id' => $order_id,
            'bank_mandiri' => '086310',
            'bank_cba' => '12345',
            'bank_bni' => '121212',
            'bank_btn' => '121212111',
            'company_web_logo' =>$company_web_logo,
            'version' => '3'
        );
        // return $mandiris;
        // return response()->json($mandiris);
        // return view('web-views.checkout-payment', compact('mandiris'));
        return view ('web-views.payment-mandiri', compact('mandiris','orders'));
        // return view ('web-views.payment-new_mandiri', compact('mandiris','orders','business_setting'));

    }
}
