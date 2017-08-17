@extends('layouts.master')

@section('title')
    Wishlist
@stop

@section('content')
@include('layouts.includes.header', array('heading'=>"Wishlist"))
<!-- Search filters -->
<div class="row">
    @if($count > 0)
    <table class="table without-border m-t-20">
        <thead class="thead-default">
            <tr>
                <th></th>
                <th>Product</th>
                <th>Price</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr class="cart-row" id='item_{{$item->id}}'>
                <td><img src="{{ url('/') }}/images/products/{{ $item->model->image }}"></td>
                <td><a href="{{ route('products.show', [$item->model->slug]) }}">{{ $item->name }}</a></td>
                <td>$<span id='sub-total_{{$item->id}}'>{{ $item->subtotal }}</span></td>
                <td class="text-center">
                    <button class='btn btn-danger btn-xs remove-item' data-product_id="{{$item->id}}" title="Remove from wishlist">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <button class='btn btn-success btn-xs add-to-cart' data-product_id="{{$item->id}}" title="Add to cart">
                        <span class="glyphicon glyphicon-shopping-cart"></span>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href='{{ route('products') }}' class="btn btn-info btn-lg">
            Continue Shopping
    </a>
    
    
    <a href='{{ route('wishlist.empty') }}' class="btn btn-danger btn-lg pull-right">
            Empty Wishlist
    </a>
    
    @else
    <p>Wishlist is empty</p>
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
    $('.remove-item').click(function(){
        abortAjaxRequests();
        
        var product_id = $(this).data('product_id');
        var url = '{{ route("wishlist.remove") }}/'+product_id;
        productRequest(url);
        if(request != ""){
            request.success(function(response){
                if(response.status == "success")
                {
                    if(response.wishlistCount == 0)
                    {
                        location.reload();
                    }else{
                        $('#item_'+product_id).remove();
                        updateWishlistStats(response);
                        showSuccess(response.message);
                    }
                }else if(response.status == "error")
                {
                    showError(response.message);
                }
            });
        }
    });
    
    $('.add-to-cart').click(function(){
        var product_id = $(this).data('product_id');
        var url = '{{ route("cart.add") }}/'+product_id;
        productRequest(url);
        if(request != ""){
            request.success(function(response){
                if(response.status == "success")
                {
                    updateCartStats(response);
                    showSuccess(response.message);
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