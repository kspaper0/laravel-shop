<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Order No.: {{ $order->no }}</h3>
    <div class="box-tools">
      <div class="btn-group pull-right" style="margin-right: 10px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> List</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td>Buyer: </td>
        <td>{{ $order->user->name }}</td>
        <td>Paid at: </td>
        <td>{{ $order->paid_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr>
        <td>Payment Method: </td>
        <td>{{ $order->payment_method }}</td>
        <td>Payment No.: </td>
        <td>{{ $order->payment_no }}</td>
      </tr>
      <tr>
        <td>Receive Address: </td>
        <td colspan="3">{{ $order->address['address'] }} {{ $order->address['zip'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
      </tr>
      <tr>
        <td rowspan="{{ $order->items->count() + 1 }}">Product List</td>
        <td>Name</td>
        <td>Price</td>
        <td>Amount</td>
      </tr>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product->title }} {{ $item->productSku->title }}</td>
        <td>$ {{ $item->price }}</td>
        <td>{{ $item->amount }}</td>
      </tr>
      @endforeach
      <tr>
        <td>Total Cost: </td>
        <td>$ {{ $order->total_amount }}</td>
        <!-- 这里也新增了一个发货状态 -->
        <td>发货状态：</td>
        <td>{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</td>
      </tr>
      <!-- 订单发货开始 -->
      <!-- 如果订单未发货，展示发货表单 -->
      @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
        <!-- 加上这个判断条件 -->
        @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_SUCCESS &&
          ($order->type !== \App\Models\Order::TYPE_CROWDFUNDING || 
            $order->items[0]->product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_SUCCESS))
        <tr>
          <td colspan="4">
            <form action="{{ route('admin.orders.ship', [$order->id]) }}" method="post" class="form-inline">
              <!-- 别忘了 csrf token 字段 -->
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="form-group {{ $errors->has('express_company') ? 'has-error' : '' }}">
                <label for="express_company" class="control-label">Shipping Company: </label>
                <input type="text" id="express_company" name="express_company" value="" class="form-control" placeholder="Please type in shipping company" size="35">
                @if($errors->has('express_company'))
                  @foreach($errors->get('express_company') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <div class="form-group {{ $errors->has('express_no') ? 'has-error' : '' }}">
                <label for="express_no" class="control-label">Shipping Number: </label>
                <input type="text" id="express_no" name="express_no" value="" class="form-control" placeholder="Please type in shipping number" size="35">
                @if($errors->has('express_no'))
                  @foreach($errors->get('express_no') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <button type="submit" class="btn btn-success" id="ship-btn">Delivery</button>
            </form>
          </td>
        </tr>
        @endif
      @else
      <!-- 否则展示物流公司和物流单号 -->
      <tr>
        <td>Shipping Company: </td>
        <td>{{ $order->ship_data['express_company'] }}</td>
        <td>Shipping Number: </td>
        <td>{{ $order->ship_data['express_no'] }}</td>
      </tr>
      @endif
      <!-- 订单发货结束 -->

      @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
      <tr>
        <td>Refund Status: </td>
        <td colspan="2">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}, Reason: {{ $order->extra['refund_reason'] }}</td>
        <td>
          <!-- 如果订单退款状态是已申请，则展示处理按钮 -->
          @if($order->refund_status === \App\Models\Order::REFUND_STATUS_APPLIED)
          <button class="btn btn-sm btn-success" id="btn-refund-agree">Agree</button>
          <button class="btn btn-sm btn-danger" id="btn-refund-disagree">Disagree</button>
          @endif
        </td>
      </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>
<script>
$(document).ready(function() {
  // 不同意 按钮的点击事件
  $('#btn-refund-disagree').click(function() {
    swal({
      title: 'Please type in the reason why it is refused to refund',
      input:'text',
      showCancelButton: true,
      confirmButtonText: "Confirm",
      cancelButtonText: "Cancel",
      showLoaderOnConfirm: true,
      preConfirm: function(inputValue){
        if (!inputValue) {
          swal('The reason is required', '', 'error')
          return false;
        }
      // Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求
        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({   
          // 将请求变成 JSON 字符串
            agree: false,  
            // 拒绝申请
            reason: inputValue,
            // 带上 CSRF Token
            // Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token
            _token: LA.token,
          }),
          contentType: 'application/json',  
          // 请求的数据格式为 JSON

          });
        },
        allowOutsideClick: () => !swal.isLoading()
      }).then(function (ret) {
        // 如果用户点击了『取消』按钮，则不做任何操作
        if (ret.dismiss === 'cancel') {
          return;
      }
      swal({
        title: 'Successfully',
        type: 'success'
      }).then(function() {
        // 用户点击 swal 上的按钮时刷新页面
        location.reload();
      });
    });
  });

  // 同意按钮的点击事件
  $('#btn-refund-agree').click(function() {
    swal({
      title: 'Are you agreed to refund the order ?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: "Confirm",
      cancelButtonText: "Cancel",
      showLoaderOnConfirm: true,
      preConfirm: function() {

        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({
            agree: true, // 代表同意退款
            _token: LA.token,
          }),
          contentType: 'application/json',
        });
      }
    }).then(function (ret) {
      // 如果用户点击了『取消』按钮，则不做任何操作
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: 'Successfully',
        type: 'success'
      }).then(function() {
        // 用户点击 swal 上的按钮时刷新页面
        location.reload();
      });
    });
  });
});
</script>