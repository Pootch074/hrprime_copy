@extends('layouts/contentNavbarLayout')

@section('title', 'Positions')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Positions</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Position</button>
    </div>

    <div class="table-responsive">
      <table id="positionTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Position Name</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($positions as $index => $position)
          <tr data-id="{{ $position->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $position->position_name }}</td>
            <td>{{ ucfirst($position->status) }}</td>
            <td class="text-nowrap">
              <div class="d-inline-flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $position->id }}"
                  data-position-name="{{ $position->position_name }}"
                  data-abbreviation="{{ $position->abbreviation }}"
                  data-status="{{ $position->status }}">
                  Edit
                </button>

                <button class="btn btn-sm btn-info req-btn"
                  data-id="{{ $position->id }}"
                  data-position-name="{{ $position->position_name }}">
                  Requirements
                </button>

                <button class="btn btn-sm btn-warning qual-btn"
                  data-id="{{ $position->id }}"
                  data-position-name="{{ $position->position_name }}">
                  Qualifications
                </button>

                <button class="btn btn-sm btn-danger delete-btn"
                  data-id="{{ $position->id }}">
                  Delete
                </button>
              </div>
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Requirements & Qualifications Modal -->
<div class="modal fade" id="reqModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-3">
        Manage Requirements & Qualifications for <span id="reqPositionName"></span>
      </h5>
      <form id="reqForm">
        <input type="hidden" name="position_id" id="reqPositionId">
        <div class="modal-body">
          <div class="mb-3">
            <label>Requirement / Qualification</label>
            <input type="text" name="requirement" id="requirement" class="form-control" placeholder="Enter requirement/qualification">
          </div>
          <button type="button" id="addRequirementBtn" class="btn btn-success mb-3">+ Add</button>

          <ul class="list-group" id="requirementsList">
            <!-- Dynamically loaded -->
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Qualifications Modal -->
<div class="modal fade" id="qualModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-3">
        Manage Qualifications for <span id="qualPositionName"></span>
      </h5>
      <form id="qualForm">
        <input type="hidden" name="position_id" id="qualPositionId">
        <div class="modal-body">
          <div class="mb-3">
            <label>Qualification</label>
            <input type="text" name="qualification" id="qualification" class="form-control" placeholder="Enter qualification">
          </div>
          <button type="button" id="addQualificationBtn" class="btn btn-success mb-3">+ Add Qualification</button>

          <ul class="list-group" id="qualificationsList"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Position</h5>
      <form id="positionForm">
        <div class="modal-body">
          @include('content.planning.position_form')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Edit Position</h5>
      <form id="editPositionForm">
        <input type="hidden" name="id" id="editPositionId">
        <div class="modal-body">
          @include('content.planning.position_form_edit')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
      </div>
      <div class="modal-body">Are you sure you want to delete this position?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $('#openModalBtn').click(function() {
    $('#positionForm')[0].reset();
    new bootstrap.Modal(document.getElementById('positionModal')).show();
  });

  $('#positionForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("position.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Position added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('positionModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  $(document).on('click', '.edit-btn', function() {
    let data = $(this).data();
    $('#editPositionId').val(data.id);
    $('#edit_position_name').val(data.positionName); // camelCase
    $('#edit_abbreviation').val(data.abbreviation);
    $('#edit_status').val(data.status);
    new bootstrap.Modal(document.getElementById('editPositionModal')).show();
  });


  $('#editPositionForm').submit(function(e) {
    e.preventDefault();
    let id = $('#editPositionId').val();
    $.ajax({
      url: `/planning/position/${id}/update`,
      method: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Position updated successfully!');
        bootstrap.Modal.getInstance(document.getElementById('editPositionModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  let deleteId = null;
  $(document).on('click', '.delete-btn', function() {
    deleteId = $(this).data('id');
    new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
  });

  $('#confirmDeleteBtn').click(function() {
    $.ajax({
      url: `/planning/position/${deleteId}/delete`,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        toastr.success('Deleted successfully!');
        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
        setTimeout(() => location.reload(), 500);
      }
    });
  });

  // Open Requirements modal
  $(document).on('click', '.req-btn', function() {
    let id = $(this).data('id');
    let name = $(this).data('position-name');

    $('#reqPositionId').val(id);
    $('#reqPositionName').text(name);

    $.get(`/planning/position/${id}/requirements`, function(data) {
      let list = $('#requirementsList').empty();
      if (data.length === 0) list.append('<li class="list-group-item">No requirements yet.</li>');
      data.forEach(req => {
        list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
        ${req.requirement}
        <button class="btn btn-sm btn-danger delete-req" data-id="${req.id}">X</button>
      </li>`);
      });
    });

    new bootstrap.Modal(document.getElementById('reqModal')).show();
  });

  // Add Requirement
  $('#addRequirementBtn').click(function() {
    let id = $('#reqPositionId').val();
    let requirement = $('#requirement').val();
    $.post(`/planning/position/${id}/requirements/store`, {
      requirement: requirement,
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Requirement added!');
      $('.req-btn[data-id="' + id + '"]').click(); // reload list
    });
  });

  // Delete Requirement
  $(document).on('click', '.delete-req', function() {
    let id = $(this).data('id');
    $.post(`/planning/requirements/${id}/delete`, {
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Requirement deleted!');
      $('.req-btn[data-id="' + $('#reqPositionId').val() + '"]').click();
    });
  });

  // Open Qualifications modal
  $(document).on('click', '.qual-btn', function() {
    let id = $(this).data('id');
    let name = $(this).data('position-name');

    $('#qualPositionId').val(id);
    $('#qualPositionName').text(name);

    $.get(`/planning/position/${id}/qualifications`, function(data) {
      let list = $('#qualificationsList').empty();
      if (data.length === 0) list.append('<li class="list-group-item">No qualifications yet.</li>');
      data.forEach(q => {
        list.append(`<li class="list-group-item d-flex justify-content-between align-items-center">
        ${q.qualification}
        <button class="btn btn-sm btn-danger delete-qual" data-id="${q.id}">X</button>
      </li>`);
      });
    });

    new bootstrap.Modal(document.getElementById('qualModal')).show();
  });

  // Add Qualification
  $('#addQualificationBtn').click(function() {
    let id = $('#qualPositionId').val();
    let qualification = $('#qualification').val();
    alert(qualification);
    $.post(`/planning/position/${id}/qualifications/store`, {
      qualification: qualification,
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Qualification added!');
      $('.qual-btn[data-id="' + id + '"]').click(); // reload list
    });
  });

  // Delete Qualification
  $(document).on('click', '.delete-qual', function() {
    let id = $(this).data('id');
    $.post(`/planning/qualifications/${id}/delete`, {
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function() {
      toastr.success('Qualification deleted!');
      $('.qual-btn[data-id="' + $('#qualPositionId').val() + '"]').click();
    });
  });


  $('#positionTable').DataTable();
</script>
@endpush