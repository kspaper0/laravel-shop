@extends('layouts.app')
@section('title', 'Postal Address List')

@section('content')
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="panel panel-default">
  <div class="panel-heading">Postal Address List</div>
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
      @foreach($addresses as $address)
      <tr>
        <td>{{ $address->contact_name }}</td>
        <td>{{ $address->full_address }}</td>
        <td>{{ $address->zip }}</td>
        <td>{{ $address->contact_phone }}</td>
        <td>
          <button class="btn btn-primary">Edit</button>
          <button class="btn btn-danger">Delete</button>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
</div>
</div>
@endsection