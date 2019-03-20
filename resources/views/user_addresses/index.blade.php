@extends('layouts.app')
@section('title', 'Postal Address List')

@section('content')
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="panel panel-default">
  <div class="panel-heading">
  Postal Address List
  <a href="{{ route('user_addresses.create') }}" class="pull-right">Add New Postal Address</a>
  </div>
  <div class="panel-body">
    <table class="table table-bordered table-striped">
      <thead>
      <tr>
        <th>Recipients</th>
        <th>Address</th>
        <th>Zip Code</th>
        <th>Mobile No.</th>
        <th>Operation</th>
      </tr>
      </thead>
      <tbody>
      @if(!count($addresses))
      <tr>
        <td class="text-center" colspan="5">
          <a class="btn btn-primary" href="{{ route('user_addresses.create') }}">
            Please leave some postal addresses !
          </a>
        </td>
      </tr>
      @else
        @foreach($addresses as $address)
        <tr>
          <td>{{ $address->contact_name }}</td>
          <td>{{ $address->full_address }}</td>
          <td>{{ $address->zip }}</td>
          <td>{{ $address->contact_phone }}</td>
          <td>
            <a href="{{ route('user_addresses.edit', ['user_address' => $address->id]) }}" class="btn btn-primary">Edit</a>
            <!-- <form action="{{ route('user_addresses.destroy', ['user_address' => $address->id]) }}" method="post" style="display: inline-block">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button class="btn btn-danger" type="submit">Delete</button>
            </form> -->

            <!-- 把之前删除按钮的表单替换成这个按钮，data-id 属性保存了这个地址的 id，在 js 里会用到 -->
            <button class="btn btn-danger btn-del-address" type="button" data-id="{{ $address->id }}">删除</button>
          </td>
        </tr>
        @endforeach
      @endif
      </tbody>
    </table>
  </div>
</div>
</div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
$(document).ready(function() {
  // 删除按钮点击事件
  $('.btn-del-address').click(function() {
    // 获取按钮上 data-id 属性的值，也就是地址 ID
    var id = $(this).data('id');
    // 调用 sweetalert
    swal({
        title: "Are you sure to delete this address ?",
        icon: "warning",
        buttons: ['Cancel', 'Confirm'],
        dangerMode: true,
      })
    .then(function(willDelete) { // 用户点击按钮后会触发这个回调函数
      // 用户点击确定 willDelete 值为 true， 否则为 false
      // 用户点了取消，啥也不做
      if (!willDelete) {
        return;
      }
      // 调用删除接口，用 id 来拼接出请求的 url
      axios.delete('/user_addresses/' + id)
        .then(function(data) {
          swal({
            title: data.data.message,
            text: "Deleted Successfully",
            icon: "success"
          })
        })
        .then(function () {
          // 请求成功之后重新加载页面
          location.reload();
        })
    });
  });
});
</script>
@endsection