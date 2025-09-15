@extends('layouts/contentNavbarLayout')

@section('title', 'View Employee')

@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 style="color: #1d4bb2;" class="mb-0">Employee Details</h4>
    </div>

    <div class="d-flex justify-content-center mb-4">
      @if($employee->profile_image)
        <div style="width: 200px; height: 200px; border: 2px solid #1d4bb2; border-radius: 50%; overflow: hidden;">
          <img src="{{ asset($employee->profile_image) }}"
               alt="Profile Image"
               style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      @else
        <div style="width: 200px; height: 200px; border: 4px solid #6c757d; border-radius: 50%; overflow: hidden;">
          <img src="{{ asset('default-user.png') }}"
               alt="No Photo"
               style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      @endif
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

   <form>
  <div class="row g-4">
    {{-- ID No --}}
    <div class="col-md-4">
      <label for="employee_id" class="form-label fw-bold">ID No.</label>
      <input type="text" id="employee_id" class="form-control" value="{{ strtoupper($employee->employee_id) }}" readonly>
    </div>

    {{-- Username --}}
    <div class="col-md-4">
      <label for="username" class="form-label fw-bold">Username</label>
      <input type="text" id="username" class="form-control" value="{{ $employee->username }}" readonly>
    </div>

    {{-- Email --}}
    <div class="col-md-4">
      <label for="email" class="form-label fw-bold">Email</label>
      <input type="email" id="email" class="form-control" value="{{ $employee->email }}" readonly>
    </div>

    {{-- Full Name --}}
    <div class="col-md-6">
      <label for="full_name" class="form-label fw-bold">Full Name</label>
      <input type="text" id="full_name" class="form-control"
        value="{{ strtoupper($employee->first_name) }} {{ strtoupper($employee->middle_name) }} {{ strtoupper($employee->last_name) }} {{ strtoupper($employee->extension_name) }}"
        readonly>
    </div>

    {{-- Employment Status --}}
    <div class="col-md-6">
      <label for="employment_status" class="form-label fw-bold">Employment Status</label>
      <input type="text" id="employment_status" class="form-control" value="{{ strtoupper($employee->employmentStatus->abbreviation ?? '-') }}" readonly>
    </div>

    {{-- Section --}}
    <div class="col-md-6">
      <label for="section" class="form-label fw-bold">Section</label>
      <input type="text" id="section" class="form-control" value="{{ strtoupper($employee->section->abbreviation ?? '-') }}" readonly>
    </div>

    {{-- Division --}}
    <div class="col-md-6">
      <label for="division" class="form-label fw-bold">Division</label>
      <input type="text" id="division" class="form-control" value="{{ strtoupper($employee->division->abbreviation ?? '-') }}" readonly>
    </div>
  </div>

  {{-- Action Buttons --}}
  <div class="mt-4 d-flex justify-content-end">
    <a href="{{ route('employee.view-blade') }}" class="btn btn-outline-secondary me-2">Back</a>
    <a href="{{ url('/employee/' . $employee->id . '/edit') }}" class="btn btn-primary">Edit</a>
  </div>
</form>

    </div>
  </div>
</div>
@endsection
