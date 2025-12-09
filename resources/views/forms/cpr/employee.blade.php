@extends('layouts/contentNavbarLayout')

@section('title', 'CPR Employees')

@section('content')
<div class="card p-4">

  <div class="d-flex justify-content-between mb-3">
    <h4 class="fw-bold">CPR – Employee Ratings</h4>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCprEmployeeModal">
      <i class="bx bx-plus"></i> Add Employee Rating
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered" id="cprEmployeeTable">
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
            <form action="{{ route('cpremployee.destroy', $ce->id) }}" method="POST">
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

</div>

<!-- Modal Add CPR Employee -->
<div class="modal fade" id="addCprEmployeeModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('cpremployee.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add CPR Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Employee ID</label>
          <input type="text" name="employee_id" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">CPR ID</label>
          <input type="number" name="cpr_id" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Rating</label>
          <input type="number" name="rating" class="form-control" min="1" max="5" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
  $(document).ready(function() {
    $('#cprEmployeeTable').DataTable();
  });
</script>
@endsection
