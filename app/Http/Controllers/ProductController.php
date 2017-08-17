<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Cart;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $filters = [
            'limit'         => 8,
            'offset'        => 0,
            'order_by'      => 'name',
            'order_by_type' => 'asc'
        ];

        $products = Product::getAll($filters);
        $products_list = view('partials.products_list', compact('products'))->render();
        return view('products', compact('products_list'));
    }
    
    /**
     * Returns a list of products with ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request){
        $filters    = $request->all();
        $products     = Product::getAll($filters);
        $products_list= view('partials.products_list', compact('products'));
        $response   = [
            'status'=> 'success',
            'data'  => $products_list->render()
        ];
        return json_encode($response);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product        = Product::where('slug', $slug)->first();
        $cartFound      = $this->cart->search(function ($cartItem) use ($product) {
                            return $cartItem->id === $product->id;
                          });
                          
        $wishlistFound  =  $this->wishlist->instance('wishlist')->search(function ($cartItem) use ($product) {
                            return $cartItem->id === $product->id;
                          });

        $in_cart = true;
        if($cartFound->isEmpty()){
            $in_cart = false;
        }
        
        $in_wishlist = true;
        if($wishlistFound->isEmpty()){
            $in_wishlist = false;
        }
        if(empty($product)){
            abort(404, 'Invalid URL');
        }
        return view('product_detail', compact('product', 'in_cart', 'in_wishlist'));
    }
}
