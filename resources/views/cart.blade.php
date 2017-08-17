@extends('layouts.master')

@section('title')
    Shopping Cart
@stop

@section('content')
@include('layouts.includes.header', array('heading'=>"Shopping Cart"))
<!-- Search filters -->
<div class="row">
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
            <tr class="cart-row">
                <td><img src="{{ url('/') }}/images/products/{{ $item->model->image }}"></td>
                <td><a href="{{ route('products.show', [$item->model->slug]) }}">{{ $item->name }}</a></td>
                <td><div class="row col-xs-6"><input id="qty_{{$item->id}}" data-product_id="{{$item->id}}" class="form-control qty" type="number" min="1" value="{{ $item->qty }}"></div></td>
                <td>$<span id='sub-total_{{$item->id}}'>{{ $item->subtotal }}</span></td>
                <td class="text-center">
                    <a href="{{ route('cart.remove', $item->id) }}">
                        <button type="button" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </a>
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