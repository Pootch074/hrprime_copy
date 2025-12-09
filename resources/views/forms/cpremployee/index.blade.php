@extends('layouts/contentNavbarLayout')

@section('title', 'OutSlip List')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<div class="card p-4">
  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">CPR – Employee Ratings</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCprEmployeeModal">
      <i class="bx bx-plus"></i> Add Employee Rating
    </button>
  </div>

  <table id="outslipTable" class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Employee ID</th>
        <th>Rating</th>
        <th>CPR ID</th>
        <th>Date Created</th>
        <th width="120">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($cprEmployees as $ce)
      <tr>
        <td>{{ $ce->id }}</td>
        <td>{{ $ce->employee_id }}</td>
        <td>{{ $ce->rating }}</td>
        <td>{{ $ce->cpr_id }}</td>
        <td>{{ $ce->created_at->format('Y-m-d') }}</td>
        <td>
          <form action="{{ route('employee.destroy', $ce->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>

  </table>
</div>

<!-- Modal Add CPR Employee -->
<div class="modal fade" id="addCprEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('employee.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add CPR Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Employee ID (auto-filled, hidden) -->
        <input type="hidden" name="employee_id" value="{{ $userId }}">

        <!-- Show Employee ID as read-only -->
        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input type="text" class="form-control" value="{{ $userId }}" readonly>
        </div>

        <!-- CPR selection -->
        <div class="mb-3">
          <label class="form-label">CPR</label>
          <select name="cpr_id" class="form-select" required>
            <option value="" disabled selected>Select CPR</option>
            @foreach($cprs as $cpr)
            <option value="{{ $cpr->id }}">
              {{ $cpr->semester }} ({{ \Carbon\Carbon::parse($cpr->rating_period_start)->format('M Y') }})
            </option>
            @endforeach
          </select>
        </div>

        <!-- Rating -->
        <div class="mb-3">
          <label class="form-label">Rating</label>
          <input type="number" name="rating" class="form-control" min="0" max="100" step="0.01" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  $(document).ready(function() {
    // Cancel (Reject) button click
    $('.rejects').click(function() {
      const id = $(this).data('id');

      // Confirmation dialog
      if (!confirm('Are you sure you want to cancel this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      // AJAX request to reject the out slip
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });

    // Approve button click
    $('.approve').click(function() {
      const id = $(this).data('id');

      if (!confirm('Are you sure you want to approve this Out Slip?')) {
        return; // Stop if user clicks "Cancel"
      }

      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });
  });
</script>


<script>
  $(document).ready(function() {
    $('.approve').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/approve', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.success(res.message);
        location.reload();
      });
    });

    $('.reject').click(function() {
      const id = $(this).data('id');
      $.post('/forms/outslips/' + id + '/reject', {
        _token: '{{ csrf_token() }}'
      }, function(res) {
        toastr.error(res.message);
        location.reload();
      });
    });
  });

  // DataTable Init
  jQuery(function($) {
    $('#outslipTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endsection
