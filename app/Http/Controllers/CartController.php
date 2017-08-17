<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateQuantityRequest;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Cart;

class CartController extends Controller
{
    public function index()
    {
        $items      = Cart::content();
        $subTotal   = Cart::subtotal();
        $tax        = Cart::tax();
        $total      = Cart::total();
        $count      = Cart::count();
        return view('cart', compact('items', 'subTotal', 'tax', 'total', 'count'));
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
            $found      = Cart::search(function ($cartItem) use ($product) {
                            return $cartItem->id === $product->id;
                          });
                          
                          
            if($found->isEmpty()){
                $product_details = [
                    'id'    => $product->id, 
                    'name'  => $product->name, 
                    'qty'   => 1, 
                    'price' => $product->price
                ];
                $item = Cart::add($product_details)->associate(Product::class);
                $response['message'] = '"'.$product->name.'" is added in the cart';
                
            }else{
                $response['message'] = '"'.$product->name.'" is already in the cart';
                
            }
            $response['cartCount'] = Cart::count();
            $response['cartTotal'] = Cart::total();
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
            $found      = Cart::search(function ($cartItem) use ($product) {
                            return $cartItem->id === $product->id;
                          });
                          
                          
            if($found->isEmpty()){
                $response['message'] = '"'.$product->name.'" is already removed from the cart';
                
            }else{
                $rowId = array_keys($found->toArray())[0];
                Cart::remove($rowId);
                $response['message'] = '"'.$product->name.'" removed from the cart';
            }
            $response['cartCount'] = Cart::count();
            $response['cartTotal'] = Cart::total();
            $response['status'] = 'success';
        }
        return $response;
    }

    public function updateQty(UpdateQuantityRequest $request, $id = null)
    {
        $product = Product::find($id);
        if(empty($product))
        {
            $response = [
                'status'        => 'error',
                'message'       => 'Invalid Request'
            ];
            
        } else {
            $found      = Cart::search(function ($cartItem) use ($product) {
                            return $cartItem->id === $product->id;
                          });
                          
                          
            if($found->isEmpty()){
                $product_details = [
                    'id'    => $product->id, 
                    'name'  => $product->name, 
                    'qty'   => $request->input('quantity'), 
                    'price' => $product->price
                ];
                $item = Cart::add($product_details)->associate(Product::class);
                $response['message'] = '"'.$product->name.'" quantity updated in the cart';
                
            } else {
                $item   = $found->first();
                Cart::update($item->rowId, $request->quantity);
                $response['subtotal']   = $item->subtotal;
                $response['message']    = '"'.$product->name.'" quantity updated in the cart';
            }
            $this->cartCalculations($response);
            $response['status'] = 'success';
        }
        return $response;
    }
    private function cartCalculations(&$response){
        $response['cartCount']      = Cart::count();
        $response['cartSubTotal']   = Cart::subtotal();
        $response['cartTax']        = Cart::tax();
        $response['cartTotal']      = Cart::total();
    }

    public function emptyCart()
    {   
        if(Cart::count() > 0){
            Cart::destroy();
            session()->flash('success', 'All items are removed from Shopping Cart');
        }else{
            session()->flash('success', 'Shopping Cart is already empty');
        }
        return back();
    }
}
