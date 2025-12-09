@extends('layouts.contentNavbarLayout')

@section('title', 'CPR List')

@section('content')
<div class="card p-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>CPR List</h4>
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCprModal">
      Add New CPR
    </button>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Rating Period</th>
        <th>Semester</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cprs as $cpr)
      <tr>
        <td>{{ $cpr->rating_period_start }}</td>
        <td>{{ $cpr->semester }}</td>
        <td>
          <a href="{{ route('forms.cpr.edit', $cpr->id) }}" class="btn btn-sm btn-primary">Edit</a>
          <form action="{{ route('forms.cpr.destroy', $cpr->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete CPR?')">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">No CPRs found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<!-- Modal for Add CPR -->
<div class="modal fade" id="addCprModal" tabindex="-1" aria-labelledby="addCprModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('forms.cpr.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addCprModalLabel">Add New CPR</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Rating Period Start -->
          <div class="mb-3">
            <label class="form-label">Rating Period </label>
            <input type="month" name="rating_period_start" class="form-control" required>
          </div>

          <!-- Semester -->
          <div class="mb-3">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" required>
              <option value="" disabled selected>Select Semester</option>
              <option value="1st Semester">1st Semester</option>
              <option value="2nd Semester">2nd Semester</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
