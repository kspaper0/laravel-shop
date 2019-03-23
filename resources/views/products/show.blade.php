@extends('layouts.app')
@section('title', $product->title)

@section('content')
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="panel panel-default">
  <div class="panel-body product-info">
    <div class="row">
      <div class="col-sm-5">
        <img class="cover" src="{{ $product->image_url }}" alt="">
      </div>
      <div class="col-sm-7">
        <div class="title">{{ $product->title }}</div>
        <div class="price"><label>Price</label><em>$</em><span>{{ $product->price }}</span></div>
        <div class="sales_and_reviews">
          <div class="sold_count">Total Sales <span class="count">{{ $product->sold_count }}</span></div>
          <div class="review_count">Total Comments <span class="count">{{ $product->review_count }}</span></div>
          <div class="rating" title="Rating {{ $product->rating }}">Rating <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
        </div>
        <div class="skus">
          <label>Chosen</label>
          <div class="btn-group" data-toggle="buttons">
            @foreach($product->skus as $sku)
              <label 
                class="btn btn-default sku-btn {{ $loop->first ? 'active' : '' }}" 
                data-price="{{ $sku->price }}"
                data-stock="{{ $sku->stock }}"
                data-toggle="tooltip"
                title="{{ $sku->description }}" 
                data-placement="bottom">
                <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
              </label>
            @endforeach
          </div>
        </div>
        <div class="cart_amount"><label>Amounts</label><input type="text" class="form-control input-sm" value="1"><span class="stock"></span></div>
        <div class="buttons">
          <button class="btn btn-success btn-favor">❤ Favourites</button>
          <button class="btn btn-primary btn-add-to-cart">Add to Shopping Cart</button>
        </div>
      </div>
    </div>
    <div class="product-detail">
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab">Product Detail</a></li>
        <li role="presentation"><a href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab">User Review</a></li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="product-detail-tab">
          {!! $product->description !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
    $('.product-info .price span').text($('.sku-btn').data('price'));
    $('.product-info .stock').text('Stock:' + $('.sku-btn').data('stock'));
    $('.sku-btn').click(function () {
      $('.product-info .price span').text($(this).data('price'));
      $('.product-info .stock').text('Stock:' + $(this).data('stock'));
    });
  });
</script>
@endsection