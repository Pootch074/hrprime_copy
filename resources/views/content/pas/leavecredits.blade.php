
@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Monthly Leave Credits Summary')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Leave Credits Summary</h4>
      @if(session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
      @endif
      <!-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
          <div class="alert alert-warning">{{ session('error') }}</div>
      @endif -->

    </div>

    <div class="table-responsive">
      <table id="leavecreditsTable" class="table">
      <thead class="table-light">
        <tr>
          <th style="width: 0;">ID No.</th>
          <th>Employee Name</th>

        </tr>
      </thead>
      <tbody>
        @foreach($users as $credit)
        <tr>
          <td>{{ $credit->employee_id }}</td>
          <td>{{ Str::upper($credit->first_name) }}
            {{ Str::upper($credit->middle_name) }}
            {{ Str::upper($credit->last_name) }}
            {{ Str::upper($credit->extension_name) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
</div>



@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
 $('#leavecreditsTable').DataTable({
  columnDefs: [
    { targets: 0, width: "50px", visible: true, searchable: false }
  ]
});
</script>

@endpush
