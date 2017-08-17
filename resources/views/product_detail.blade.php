@extends('layouts.master')

@section('title')
    Products | {{ $product->title }}
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
        <button class="btn-danger" id="remove-cart_{{ $product->id }}" @if(!$in_cart)style="display: none"@endif onclick="removeFromCart(this)" data-product_id="{{ $product->id }}">
            <span class="glyphicon glyphicon-shopping-cart"></span> Remove From Cart
        </button>
        <button class="btn-success" id="add-cart_{{ $product->id }}" @if($in_cart)style="display: none"@endif onclick="addToCart(this)" data-product_id="{{ $product->id }}">
            <span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
        </button>
        @if($in_cart)
        <button class="btn-danger">
            <span class="glyphicon glyphicon-heart"  id="remove-fav_{{ $product->id }}"  onclick="removeFromFav(this)" data-product_id="{{ $product->id }}"  title="Remove from favorites"></span>
        </button>
        @else
        <button class="btn-primary" id="add-fav_{{ $product->id }}"  onclick="addToFav(this)" data-product_id="{{ $product->id }}" title="Add to favorites">
            <span class="glyphicon glyphicon-heart-empty"></span>
        </button>
        @endif
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
                $(obj).hide();
                $('#remove-cart_'+product_id).show();
                updateCartStats(response);
                showSuccess(response.message);
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
</script>
@stop