@extends('layouts.master')

@section('title')
{{ $product->name }}
@stop

@section('content')
@include('layouts.includes.header', array('heading'=>$product->name, 'subHeading'=>"$".$product->price))
<!-- Product Row -->
<div class="row">
    <div class="col-md-4">
        <img class="img-responsive" src="/images/products/{{ $product->image }}" alt="Product Image">
    </div>
    <div class="col-md-8">
        <p>
            <strong>Description: </strong>{{ $product->description }}
        </p>
        <button class="btn-danger btn-lg" id="remove-cart_{{ $product->id }}" @if(!$in_cart)style="display: none"@endif onclick="removeFromCart(this)" data-product_id="{{ $product->id }}">
            <span class="glyphicon glyphicon-shopping-cart"></span> Remove From Cart
        </button>
        <button class="btn-success btn-lg" id="add-cart_{{ $product->id }}" @if($in_cart)style="display: none"@endif onclick="addToCart(this)" data-product_id="{{ $product->id }}">
            <span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
        </button>
        <button class="btn-danger btn-lg pull-right" id="remove-wishlist_{{ $product->id }}" @if(!$in_wishlist)style="display: none"@endif onclick="removeFromWishlist(this)" data-product_id="{{ $product->id }}"  title="Remove from wishlist">
            <span class="glyphicon glyphicon-heart"></span>
        </button>
        <button class="btn-default btn-lg pull-right" id="add-wishlist_{{ $product->id }}" @if($in_wishlist)style="display: none"@endif onclick="addToWishlist(this)" data-product_id="{{ $product->id }}" title="Add to wishlist">
            <span class="glyphicon glyphicon-heart-empty"></span>
        </button>
    </div>
</div>
<hr>
@endsection

@section('foot_asset')
@parent
<script>

$(document).ready(function(e){
    
});

function addToCart(obj){
    var product_id = $(obj).data('product_id');
    var url = '{{ route("cart.add") }}/'+product_id;
    productRequest(url);
    if(request != ""){
        request.success(function(response){
            if(response.status == "success")
            {
                window.location = "{{ route('cart') }}";
//                $(obj).hide();
//                $('#remove-cart_'+product_id).show();
//                updateCartStats(response);
//                showSuccess(response.message);
            }else if(response.status == "error")
            {
                showError(response.message);
            }
        });
    }
}

function removeFromCart(obj){
    var product_id = $(obj).data('product_id');
    var url = '{{ route("cart.remove") }}/'+product_id;
    productRequest(url);
    if(request != ""){
        request.success(function(response){
            if(response.status == "success")
            {
                $(obj).hide();
                $('#add-cart_'+product_id).show();
                updateCartStats(response);
                showSuccess(response.message);
            }else if(response.status == "error")
            {
                showError(response.message);
            }
        });
    }
}
function addToWishlist(obj){
    var product_id = $(obj).data('product_id');
    var url = '{{ route("wishlist.add") }}/'+product_id;
    productRequest(url);
    if(request != ""){
        request.success(function(response){
            if(response.status == "success")
            {
                $(obj).hide();
                $('#remove-wishlist_'+product_id).show();
                updateWishlistStats(response);
                showSuccess(response.message);
            }else if(response.status == "error")
            {
                showError(response.message);
            }
        });
    }
}

function removeFromWishlist(obj){
    var product_id = $(obj).data('product_id');
    var url = '{{ route("wishlist.remove") }}/'+product_id;
    productRequest(url);
    if(request != ""){
        request.success(function(response){
            if(response.status == "success")
            {
                $(obj).hide();
                $('#add-wishlist_'+product_id).show();
                updateWishlistStats(response);
                showSuccess(response.message);
            }else if(response.status == "error")
            {
                showError(response.message);
            }
        });
    }
}
</script>
@stop