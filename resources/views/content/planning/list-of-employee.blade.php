@extends('layouts/contentNavbarLayout')

@section('title', 'Employee List')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
@if(session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="table-responsive">
      <table id="empTable" class="table">
        <thead class="table-light">
          <tr>
            <th style="width: 0;">ID No.</th>
            <th>Employee Name</th>
            <th>Employment Status</th>
            <th>Section</th>
            <th>Division</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($employees as $employee)
          <tr>
            <td>{{ $employee->employee_id }}</td>
            <td>{{ Str::upper($employee->first_name.' '.$employee->middle_name.' '.$employee->last_name.' '.$employee->extension_name) }}</td>
            <td>{{ Str::upper($employee->employmentStatus->abbreviation ?? '') }}</td>
            <td>{{ Str::upper($employee->section->abbreviation ?? '') }}</td>
            <td>{{ Str::upper($employee->division->abbreviation ?? '') }}</td>
            <td>{{ Str::lower($employee->username) }}</td>
            <td>{{ Str::lower($employee->role) }}</td>
            <td class="text-capitalize">{{ $employee->status }}</td>
            <td>
              <!-- View Profile Button -->
              <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                data-bs-target="#viewProfileModal{{ $employee->id }}">
                <i class="bi bi-person-circle me-1"></i> View Profile
              </button>
            </td>
          </tr>

          <!-- View Profile Modal -->
          <div class="modal fade" id="viewProfileModal{{ $employee->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Profile: {{ $employee->first_name }} {{ $employee->last_name }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="row mb-3">
                    <div class="col-md-6"><b>Employee ID:</b> {{ $employee->employee_id }}</div>
                    <div class="col-md-6"><b>Username:</b> {{ $employee->username }}</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6"><b>Employment Status:</b> {{ Str::upper($employee->employmentStatus->abbreviation ?? '') }}</div>
                    <div class="col-md-6"><b>Role:</b> {{ Str::lower($employee->role) }}</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6"><b>Section:</b> {{ Str::upper($employee->section->abbreviation ?? '') }}</div>
                    <div class="col-md-6"><b>Division:</b> {{ Str::upper($employee->division->abbreviation ?? '') }}</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6"><b>Status:</b> {{ $employee->status }}</div>
                  </div>

                  <hr>
                  <!-- Assign Role Form -->
                  <form action="{{ route('employee.assignRole', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                      <label for="role{{ $employee->id }}" class="form-label">Assign Role</label>
                      <select name="role" id="role{{ $employee->id }}" class="form-select" required>
                        <option value="">-- Choose Role --</option>
                        @foreach(['Employee', 'HR-Planning', 'HR-PAS', 'HR-L&D', 'HR-Welfare'] as $role)
                        <option value="{{ $role }}" {{ $employee->role === $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-save me-1"></i> Save Role
                    </button>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

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
  $('#empTable').DataTable({
    columnDefs: [{
      targets: 0,
      width: "50px",
      visible: true,
      searchable: false
    }]
  });
</script>
@endpush