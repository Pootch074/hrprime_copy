@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<div class="container">
  <h2>Leave Details</h2>
  <p><strong>Employee:</strong> {{ $leave->employee->full_name }}</p>
  <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
  <p><strong>From:</strong> {{ $leave->from_date }}</p>
  <p><strong>To:</strong> {{ $leave->to_date }}</p>
  <p><strong>No. of Days:</strong> {{ $leave->leave_no_wdays }}</p>
  <p><strong>Status:</strong> {{ $leave->status }}</p>
</div>
@endsection