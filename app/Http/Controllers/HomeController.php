<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price', '<>', '')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured', 1)->get()->take(8);

        $allProductIds = Product::where('featured', 1)->pluck('id');
        $dayOfYear = Carbon::now()->dayOfYear; // Dapatkan hari ke berapa dalam setahun
        $startIndex = $dayOfYear % $allProductIds->count();
        $hproducts = Product::whereIn('id', $allProductIds->slice($startIndex, 2))->get();


        return view('index', compact('slides', 'categories', 'sproducts', 'fproducts', 'hproducts'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => [
                'required',
                'numeric',
                'regex:/^(?:\+62|62|08)[1-9][0-9]{7,11}$/'
            ],
            'comment' => 'required',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        return redirect()->route('home.contact')->with('status', 'Your message has been sent successfully');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'like', "%{$query}%")->get()->take(8);
        return response()->json($results);
    }

    public function about()
    {
        return view('about');
    }
}
