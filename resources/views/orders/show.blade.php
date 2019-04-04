@extends('layouts.app')
@section('title', 'Order Review')

@section('content')
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="panel panel-default">
  <div class="panel-heading">
    <h4>Order Review</h4>
  </div>
  <div class="panel-body">
    <table class="table">
      <thead>
        <tr>
          <th>Product Info.</th>
          <th class="text-center">Price</th>
          <th class="text-center">Amount</th>
          <th class="text-right item-amount">Cost</th>
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
        <td class="sku-price text-center vertical-middle">$ {{ $item->price }}</td>
        <td class="sku-amount text-center vertical-middle">{{ $item->amount }}</td>
        <td class="item-amount text-right vertical-middle">$ {{ number_format($item->price * $item->amount, 2, '.', ',') }}</td>
      </tr>
      @endforeach
      <tr><td colspan="4"></td></tr>
    </table>
    <div class="order-bottom">
      <div class="order-info">
        <div class="line"><div class="line-label">Address: </div><div class="line-value">{{ join(' ', $order->address) }}</div></div>
        <div class="line"><div class="line-label">Remark: </div><div class="line-value">{{ $order->remark ?: '-' }}</div></div>
        <div class="line"><div class="line-label">Order No.: </div><div class="line-value">{{ $order->no }}</div></div>

        <!-- 输出物流状态 -->
        <div class="line">
          <div class="line-label">Shipping Status: </div>
          <div class="line-value">{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</div>
        </div>
        <!-- 如果有物流信息则展示 -->
        @if($order->ship_data)
        <div class="line">
          <div class="line-label">Shipping Info.: </div>
          <div class="line-value">{{ $order->ship_data['express_company'] }} {{ $order->ship_data['express_no'] }}</div>
        </div>
        @endif

      </div>
      <div class="order-summary text-right">
        <div class="total-amount">
          <span>Total Cost: </span>
          <div class="value">$ {{ $order->total_amount }}</div>
        </div>
        <div>
          <span>Status</span>
          <div class="value">
            @if($order->paid_at)
              @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                Paid
              @else
                {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
              @endif
            @elseif($order->closed)
              Closed
            @else
              Unpaid
            @endif
          </div>
          <!-- 支付按钮开始 -->
          @if(!$order->paid_at && !$order->closed)
          <div class="payment-buttons">
            <a class="btn btn-primary btn-sm" href="{{ route('payment.alipay', ['order' => $order->id]) }}">Pay by Alipay</a>
          </div>
          @endif
          <!-- 支付按钮结束 -->

          <!-- 如果订单的发货状态为已发货则展示确认收货按钮 -->
          @if($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
          <div class="receive-button">
            <!-- <form method="post" action="{{ route('orders.received', [$order->id]) }}"> -->
              <!-- csrf token 不能忘 -->
              <!-- {{ csrf_field() }} -->
              <!-- <button type="submit" class="btn btn-sm btn-success">Received</button> -->
            <!-- </form> -->
            {{-- 将原本的表单替换成下面这个按钮 --}}
            <button type="button" id="btn-receive" class="btn btn-sm btn-success">Received</button>
          </div>
          @endif

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
  $(document).ready(function() {
    // 确认收货按钮点击事件
    $('#btn-receive').click(function() {
      // 弹出确认框
      swal({
        title: "Are you sure to receive your order ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        buttons: ['Cancel', 'Confirm'],
      })
      .then(function(ret) {
        // 如果点击取消按钮则不做任何操作
        if (!ret) {
          return;
        }
        // ajax 提交确认操作
        axios.post('{{ route('orders.received', [$order->id]) }}')
          .then(function () {
            // 刷新页面
            location.reload();
          })
      });
    });

  });
</script>
@endsection