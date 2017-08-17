<?php 
$index = 0; 
$counter = 0;
$total = count($products);
?>
@foreach($products as $product)

@if(++$index == 1)
<!-- Product Row -->
<div class="row">
@endif
    <div class="col-md-3 portfolio-item">
        <div>
            <h3>
                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>
            <strong>Price: 
                @if(!empty($product->price))
                ${{ $product->price }}
                @else
                Not Available
                @endif
            </strong>
            <p>{{ str_limit($product->description, 100) }}</p>
        </div>
        <a href="{{ route('products.show', $product->slug) }}">
            <img class="img-responsive" src="images/products/{{ $product->image }}" alt="{{ $product->name }} image">
        </a>
    </div>
@if($index == 4 && $total != $counter)
<?php $index=0; ?>
</div>
<!-- /.row -->
@endif
<?php $counter++; ?>
@endforeach