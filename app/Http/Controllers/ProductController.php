<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // dd($request);

        $validated = $request->validate([
            'price_from' => 'sometimes|nullable|numeric',
            'price_to' => 'sometimes|nullable|numeric',
        ]);

        $variants = Variant::with(['variants_name'=>function($query)
        {
            $query->selectRaw('variant_id, variant')->groupBy('variant','variant_id')->get();
        }])->get();


        $products = Product::with(['product_variants_price'=>function($query) use ($request)
        {

            $query->with(['product_variant_one_relation'=>function($q) use ($request)
            {
                if($request->variant)
                {
                    $q->where('variant','like',$request->variant);
                }
                else
                {
                    $q->where('variant','like','%%');
                }
            },'product_variant_two_relation'=>function($q) use ($request)
            {
                if($request->variant)
                {
                    $q->where('variant','like',$request->variant)->orWhere('variant','like','%%');
                }
                else
                {
                    $q->where('variant','like','%%');
                }
            },'product_variant_three_relation'=>function($q) use ($request)
            {
                if($request->variant)
                {
                    $q->where('variant','like',$request->variant);
                }
                else
                {
                    $q->where('variant','like','%%');
                }
            }]);


            if ($request->price_from) {
                $query->where('price', '>=', $request->price_from);
            }
            if ($request->price_to) {
                $query->where('price', '<=', $request->price_to);
            }

        }])->whereHas('product_variants_price',function($query) use ($request)
        {
            if ($request->price_from) {
                $query->where('price', '>=', $request->price_from);
            }
            if ($request->price_to) {
                $query->where('price', '<=', $request->price_to);
            }
        });


        if($request->product_title)
        {
            $products = $products->where('title','like','%'.$request->product_title.'%');
        }

        if($request->date)
        {
            $date = Carbon::parse($request->date)->format('y-m-d');

            $products = $products->whereDate('created_at','>=',$date);
        }
        
        $products=$products->paginate(5);

        return view('products.index',compact('products','variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {   
        $product = new Product();
        
        $product->title       = $request->product_name;
        $product->sku         = $request->product_sku;
        $product->description =$request->product_description;

        $product->save();

        $variationList = [];

        foreach($request->product_variant as $items)
        {   
            foreach($items['value'] as $item)
            {
                $productVariant = new ProductVariant();

                $productVariant->variant = $item;

                $productVariant->variant_id = $items['option'];

                $productVariant->product_id = $product->id;

                $productVariant->save();

                array_push($variationList,['firstKey'=>$productVariant->id,'name'=>$item]);
            }
        }

        
        foreach($request->product_preview as $item)
        {
            $input = explode("/",$item['variant']);
            $firstKey = null;
            $firstKey = array_search($input[0], array_column($variationList, 'name'));
            
            $sceondKey = null;
            $sceondKey = array_search($input[1], array_column($variationList, 'name'));
            
            $thirdKey = null;
            $thirdKey = array_search($input[2], array_column($variationList, 'name'));
            

            $price = new ProductVariantPrice();
            if($firstKey)
            {
                $price->product_variant_one = $variationList[$firstKey]['firstKey'];
            }
            if($sceondKey)
            {
                $price->product_variant_two = $variationList[$sceondKey]['firstKey'];
            }
            if($thirdKey)
            {
                $price->product_variant_three = $variationList[$thirdKey]['firstKey'];
            }

            $price->price = $item['price'];
            $price->stock = $item['stock'];

            $price->product_id = $product->id;

            $price->save();

        }

        return redirect()->route('product.index');

        
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
