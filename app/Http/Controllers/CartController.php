<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price,)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function clear_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '>=', Cart::instance('cart')->subtotal())
                ->first();
            if (!$coupon) {
                return redirect()->back()->with('error', 'Invalid Coupon Code');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value,
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Coupon has been applied successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Coupon Code');
        }
    }


    public function calculateDiscount()
    {
        $discount = 0;

        $subtotal = intval(str_replace(',', '', Cart::instance('cart')->subtotal()));

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');

            $couponValue = floatval($coupon['value']);

            if ($coupon['type'] == 'fixed') {
                $discount = $couponValue;
            } else {
                $discount = $couponValue / 100 * $subtotal;
            }

            $subtotalAfterDiscount = $subtotal - $discount;
            $taxAfterDiscount = $subtotalAfterDiscount * floatval(config('cart.tax')) / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => intval($discount),
                'subtotal' => intval($subtotalAfterDiscount),
                'tax' => (int)$taxAfterDiscount,
                'total' => intval($totalAfterDiscount),
            ]);
        }
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back()->with('success', 'Coupon has been removed successfully');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }
}
