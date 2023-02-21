<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        
        $imageName = time().'.'.$request->file->extension();  

        $fileName = 'images/'.$imageName;

        $request->file->move(public_path('images'), $imageName);

        $product = new ProductImage();

        $product->file_path = $fileName;

        $product->product_id = Product::count()+1;

        $product->thumbnail = 1;

        $product->save();


        /* Store $imageName name in DATABASE from HERE */

        return response([
            'message' => 'Success',
        ]);
    }
}
