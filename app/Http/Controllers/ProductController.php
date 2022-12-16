<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Datatables;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('products.list_product');
    }

    public function list()
    {
        $products = Product::all();
        return datatables()->of($products)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $actionBtn = '<a  title="Edit product" class="btn btn-sm btn-warning"> Update </a> 
            <a class="btn btn-danger btn-sm" title="Delete Product" onclick="deleteproduct('.$row->id.')" >Delete</a>';
            return $actionBtn;
             
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create_product_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $product = new Product();
       $product->product_name = $request->product_name;
       $product->product_price = $request->product_price;
       $product->product_desccription = $request->product_descrition;
       $product->save();
       $product_id = $product->id;
       if ($request->hasfile('product_images')) {
        foreach ($request->product_images as $image) {
            $name = time().rand(1,100).'.'.$image->extension();
            if ($image->move(public_path('my_files/'.$request->product_name), $name)) {
                $product_image = new ProductImage();
                $product_image->product_id = $product_id;
                $product_image->product_image = $request->product_name.'/'.$name;
                $product_image->save();
            }
        }
     }
     if ($product_image) {
        return response()->json(['status' => 'success', 'data' => $product_image, 'message' => 'Success! image(s) uploaded']);
     }

     else {
        return response()->json(['status' => 'failed', 'data' => $product_image, 'message' => 'Failed! image(s) not uploaded']);
     }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product_id)
    {
        $products = Product::with('productimages')->find($product_id);
        return view('products.edit_product_form')->with('products',$products);
    }

    public function editproduct(Request $request)
    {
        $updateproduct = Product::find($request->product_id);
        $updateproduct->product_name = $request->product_name;
        $updateproduct->product_price = $request->product_price;
        $updateproduct->product_desccription = $request->product_descrition;
        $updateproduct->save();
        if($request->totalImages > 0){
            if ($request->hasfile('product_images')) {
                foreach ($request->product_images as $image) {
                    $name = time().rand(1,100).'.'.$image->extension();
                    if ($image->move(public_path('my_files/'.$request->product_name), $name)) {
                        $product_image = new ProductImage();
                        $product_image->product_id = $request->product_id;
                        $product_image->product_image = $request->product_name.'/'.$name;
                        $product_image->save();
                    }
                }
             }
        }
        if ($updateproduct) {
            return response()->json(['status' => 'success', 'data' => $updateproduct, 'message' => 'Success! Produst Updated']);
         }
    
         else {
            return response()->json(['status' => 'failed', 'data' => $updateproduct, 'message' => 'Failed! Product not Updated']);
         }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        $deleteproduct= Product::where('id',$request->id)->delete();
        if ($deleteproduct) {
            return response()->json(['status' => 'success', 'data' => $deleteproduct, 'message' => 'Deleted Product']);
         }
    
         else {
            return response()->json(['status' => 'failed', 'data' => $deleteproduct, 'message' => 'Failed!']);
         }
    }

    public function deleteimage(Request $request)
    {
        // dd($request->all());
        $deleteproductimage= ProductImage::where('id',$request->id)->delete();
        if ($deleteproductimage) {
            return response()->json(['status' => 'success', 'data' => $deleteproductimage, 'message' => 'Deleted images']);
         }
    
         else {
            return response()->json(['status' => 'failed', 'data' => $deleteproductimage, 'message' => 'Failed!']);
         }
    }
}
