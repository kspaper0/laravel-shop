@extends('layouts.app')
@section('title', 'Order List')

@section('content')
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="panel panel-default">
  <div class="panel-heading">Order List</div>
  <div class="panel-body">
    <ul class="list-group">
      @foreach($orders as $order)
      <li class="list-group-item">
        <div class="panel panel-default">
          <div class="panel-heading">
            Order Number: {{ $order->no }}
            <span class="pull-right">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
          </div>
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Product Info.: </th>
                  <th class="text-center">Price</th>
                  <th class="text-center">Amount</th>
                  <th class="text-center">Total Cost</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              @foreach($order->items as $index => $item)
              <tr>
                <td class="product-info">
                  <div class="preview">
                    <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                      <img src="{{ $item->product->image_url }}">
                    </a>
                  </div>
                  <div>
                    <span class="product-title">
                       <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
                     </span>
                    <span class="sku-title">{{ $item->productSku->title }}</span>
                  </div>
                </td>
                <td class="sku-price text-center">$ {{ $item->price }}</td>
                <td class="sku-amount text-center">{{ $item->amount }}</td>
                @if($index === 0)
                <td rowspan="{{ count($order->items) }}" class="text-center total-amount">$ {{ $order->total_amount }}</td>
                <td rowspan="{{ count($order->items) }}" class="text-center">
                  @if($order->paid_at)
                    @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                      Paid
                    @else
                      {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
                    @endif
                  @elseif($order->closed)
                    Closed
                  @else
                    Unpaid<br>
                    Please check out before {{ $order->created_at->addSeconds(config('app.order_ttl'))->format('H:i') }}<br>
                    Otherwise, the order will be closed
                  @endif
                </td>
                <td rowspan="{{ count($order->items) }}" class="text-center"><a class="btn btn-primary btn-xs" href="{{ route('orders.show', ['order'=> $order->id]) }}">View Order</a></td>
                @endif
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
    <div class="pull-right">{{ $orders->render() }}</div>
  </div>
</div>
</div>
</div>
@endsection