@extends('layouts.master')

@section('title')
    Products | All
@stop

@section('content')
@include('layouts.includes.header', array('heading'=>"Products"))
<!-- Search filters -->
<div class="row">    
    <div class="col-xs-8 col-xs-offset-2">
                <div class="input-group">
            <div class="input-group-btn search-panel">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span id="search_concept">Filter by</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#name">Name</a></li>
                  <li><a href="#descrption">Description</a></li>
                  <li class="divider"></li>
                  <li><a href="#all">All</a></li>
                </ul>
            </div>
            <input type="hidden" name="search_param" value="all" id="search_param">
            <input type="text" id="search_box" class="form-control" name="x" placeholder="Search Product">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>
    </div>
</div>
<div class="data-container">
    {!! $products_list !!}
</div>
<div class="loading">&nbsp;</div>
<hr>
@endsection

@section('foot_asset')
@parent
<script>
$(document).ready(function(e){
    window.loadingInProgress    = false;
    window.fetchAgain           = true;
    
    // for listing pagination
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() + 200 >= $(document).height()) {
            loadRecords(false);
        }
    });
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
    });
    
    var delayTimer;
    /* Search Filters */
    $('#search_box').keyup(function(e){
        if ((window.loadingInProgress === true)) {
            return false;
        };
        window.fetchAgain = true;
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            loadRecords(true);
        }, 500);
            
    });
});
function loadRecords(emptyList){
    if ((window.loadingInProgress === true) || (window.fetchAgain === false)) {
        return false;
    }
    var searchBy    = $('#search_param').val();
    var searchText  = $('#search_box').val();
    var offset      = $('.portfolio-item').length;
    if(emptyList){
        offset = 0;
    }
    var url = '{{ route("products.listing") }}';
    $.ajax({
        url: url,
        data:{
            type         : searchBy,
            search       : searchText,
            limit        : 8,
            offset       : offset,
            order_by     : 'name',
            order_by_type: 'asc'
        },
        method: 'GET',
        dataType:'html',
        beforeSend: function () {
            window.loadingInProgress = true;
            $('.loading').show();
        },
        success:function(response){
            response = $.parseJSON(response);
            if(response.status === 'success'){
                var data = response.data;
                if ( emptyList === true ) {
                    $('.data-container').empty();
                }
                if($.trim(data) === ""){
                    if(emptyList){
                        $('.data-container').append("<div>No Products Found</div>");
                    }
                    window.fetchAgain = false;
                }else{
                    $('.data-container').append(data);
                    window.fetchAgain = true;
                }
            }
        },error: function() {
            window.loadingInProgress = false;
            $('.loading').hide();
            
        },complete: function() {
            window.loadingInProgress = false;
            $('.loading').hide();
        }
    });
}
</script>
@stop