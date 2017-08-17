@extends('layouts.master')

@section('title')
    Shopping Cart
@stop

@section('content')
@include('layouts.includes.header', array('heading'=>"Shopping Cart"))
<!-- Search filters -->
<div class="row">
    @if($count > 0)
    <table class="table without-border m-t-20">
        <thead class="thead-default">
            <tr>
                <th></th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr class="cart-row" id='item_{{$item->id}}'>
                <td><img src="{{ url('/') }}/images/products/{{ $item->model->image }}"></td>
                <td><a href="{{ route('products.show', [$item->model->slug]) }}">{{ $item->name }}</a></td>
                <td><div class="row col-xs-6"><input id="qty_{{$item->id}}" data-product_id="{{$item->id}}" class="form-control qty" type="number" min="1" value="{{ $item->qty }}"></div></td>
                <td>$<span id='sub-total_{{$item->id}}'>{{ $item->subtotal }}</span></td>
                <td class="text-center">
                    <button class='btn btn-danger btn-xs remove-item' data-product_id="{{$item->id}}">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td class="text-right"><strong>Sub Total</strong></td>
                <td>$<span id='sub-total'>{{ $subTotal }}</span></td>
                <td class="text-center">
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-right"><strong>Tax</strong></td>
                <td>$<span id='tax'>{{ $tax }}</span></td>
                <td class="text-center">
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-right"><strong>Total</strong></td>
                <td><strong>$<span id='total'>{{ $total }}</span></strong></td>
                <td class="text-center">
                </td>
            </tr>
        </tbody>
    </table>
    <a href='{{ route('products') }}' class="btn btn-info btn-lg">
            Continue Shopping
    </a>
    
    
    <a href='{{ route('cart.empty') }}' class="btn btn-danger btn-lg">
            Empty Cart
    </a>
    
    <a href='#' class="btn btn-success btn-lg pull-right">
            Proceed to checkout
    </a>
    @else
    <p>Shopping Cart is empty</p>
    <a href='{{ route('products') }}' class="btn btn-info btn-lg">
        Continue Shopping
    </a>
    @endif
</div>
<div class="loading">&nbsp;</div>
<hr>
@endsection

@section('foot_asset')
@parent
<script>
$(document).ready(function(e){
    $('.qty').change(function(){
        abortAjaxRequests();
        var val = parseInt($(this).val());
        if(!$.isNumeric(val)){
            return;
        }
        
        var product_id = $(this).data('product_id');
        var url = '{{ route("cart.update-qty") }}/'+product_id;
        var data = new FormData();
        data.append('quantity', val);
        data.append('_token', '{{ csrf_token() }}');
        productRequest(url, "POST", data);
        if(request != ""){
            request.success(function(response){
                if(response.status == "success")
                {
                    $('#sub-total_'+product_id).html(response.subtotal);
                    $('#sub-total').html(response.cartSubTotal);
                    $('#tax').html(response.cartTax);
                    $('#total').html(response.cartTotal);
                    updateCartStats(response);
                    showSuccess(response.message);
                }else if(response.status == "error")
                {
                    showError(response.message);
                }
            });
        }
    });
    $('.remove-item').click(function(){
        abortAjaxRequests();
        
        var product_id = $(this).data('product_id');
        var url = '{{ route("cart.remove") }}/'+product_id;
        productRequest(url);
        if(request != ""){
            request.success(function(response){
                if(response.status == "success")
                {
                    if(response.cartCount == 0)
                    {
                        location.reload();
                    }else{
                        $('#item_'+product_id).remove();
                        $('#sub-total_'+product_id).html(response.subtotal);
                        $('#sub-total').html(response.cartSubTotal);
                        $('#tax').html(response.cartTax);
                        $('#total').html(response.cartTotal);
                        updateCartStats(response);
                        showSuccess(response.message);
                    }
                }else if(response.status == "error")
                {
                    showError(response.message);
                }
            });
        }
    });
});

function abortAjaxRequests()
{
    //kill previous requests
    if(request != ""){
        request.abort();
    }
}
</script>
@stop