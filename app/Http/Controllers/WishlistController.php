<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateQuantityRequest;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Cart;

class WishlistController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $items      = Cart::instance('wishlist')->content();
        $subTotal   = Cart::instance('wishlist')->subtotal();
        $tax        = Cart::instance('wishlist')->tax();
        $total      = Cart::instance('wishlist')->total();
        $count      = Cart::instance('wishlist')->count();
        return view('wishlist', compact('items', 'subTotal', 'tax', 'total', 'count'));
    }
    
    public function add($id = null)
    {
        $product = Product::find($id);
        if(empty($product))
        {
            $response = [
                'status'        => 'error',
                'message'       => 'Invalid Request'
            ];
            
        } else {
            $found      = Cart::instance('wishlist')->search(function ($wishlistItem) use ($product) {
                            return $wishlistItem->id === $product->id;
                          });
                          
                          
            if($found->isEmpty()){
                $product_details = [
                    'id'    => $product->id, 
                    'name'  => $product->name, 
                    'qty'   => 1, 
                    'price' => $product->price
                ];
                $item = Cart::instance('wishlist')->add($product_details)->associate(Product::class);
                $response['message'] = '"'.$product->name.'" is added in the wishlist';
                
            }else{
                $response['message'] = '"'.$product->name.'" is already in the wishlist';
                
            }
            $this->wishlistCalculations($response);
            $response['status'] = 'success';
        }
        return $response;
    }

    public function remove($id = null)
    {
        $product = Product::find($id);
        if(empty($product))
        {
            $response = [
                'status'        => 'error',
                'message'       => 'Invalid Request'
            ];
            
        } else {
            $found      = Cart::instance('wishlist')->search(function ($wishlistItem) use ($product) {
                            return $wishlistItem->id === $product->id;
                          });
                          
                          
            if($found->isEmpty()){
                $response['message'] = '"'.$product->name.'" is already removed from the wishlist';
                
            }else{
                $rowId = array_keys($found->toArray())[0];
                Cart::instance('wishlist')->remove($rowId);
                $response['message'] = '"'.$product->name.'" removed from the wishlist';
            }
            $this->wishlistCalculations($response);
            $response['status'] = 'success';
        }
        return $response;
    }

    private function wishlistCalculations(&$response){
        $response['wishlistCount']      = Cart::instance('wishlist')->count();
    }

    public function emptyCart()
    {   
        if(Cart::instance('wishlist')->count() > 0){
            Cart::instance('wishlist')->destroy();
            session()->flash('success', 'All items are removed from Wishlist');
        }else{
            session()->flash('success', 'Wishlist is already empty');
        }
        return back();
    }
}
