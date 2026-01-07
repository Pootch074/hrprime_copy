@extends('layouts/contentNavbarLayout')
@section('title', 'User Management')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <h4 class="mb-3 text-primary">List of Users</h4>
    <div class="table-responsive">
      <table id="usersTable" class="table">
        <thead class="table-light">
          <tr>
            <th style="width:5%;">No.</th>
            <th style="width:20%;">Name</th>
            <th style="width:20%;">Division</th>
            <th style="width:25%;">Section</th>
            <th style="width:10%;">Status</th>
            <th style="width:20%;">Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-3" id="userModalTitle">Edit User</h5>
      <form id="userForm">
        <div class="modal-body">
          <input type="hidden" id="user_id" name="user_id">
          <div class="row">
            <div class="col-md-6 mb-3"><label>First Name</label><input type="text" id="first_name" class="form-control text-uppercase" readonly></div>
            <div class="col-md-6 mb-3"><label>Middle Name</label><input type="text" id="middle_name" class="form-control text-uppercase" readonly></div>
            <div class="col-md-6 mb-3"><label>Last Name</label><input type="text" id="last_name" class="form-control text-uppercase" readonly></div>
            <div class="col-md-6 mb-3"><label>Extension Name</label><input type="text" id="extension_name" class="form-control text-uppercase" readonly></div>
            <div class="col-md-6 mb-3">
              <label>Division</label>
              <select id="division_id" name="division_id" class="form-control" required>
                <option value="">Select Division</option>
                @foreach($divisions as $division)
                  <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Section</label>
              <select id="section_id" name="section_id" class="form-control" required>
                <option value="">Select Section</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" id="saveUserBtn">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Deactivation Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deactivateForm">
        <div class="modal-header">
          <h5 class="modal-title">Deactivate User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deactivate_user_id" name="user_id">
          <div class="mb-3">
            <label for="reason" class="form-label">Reason for Deactivation</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Deactivate</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
const table = $('#usersTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('user-management.list') }}",
    columns: [
        { data: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'name' },
        { data: 'division' },
        { data: 'section' },
        { data: 'is_active' },
        { data: 'action', orderable:false, searchable:false },
    ]
});

// Load Sections for Division
function loadSections(divisionId, selected = null) {
    if (!divisionId) { $('#section_id').html('<option value="">Select Section</option>'); return; }
    $.get(`/planning/user-management/sections/${divisionId}`, function(data) {
        let options = '<option value="">Select Section</option>';
        data.forEach(s => options += `<option value="${s.id}">${s.name}</option>`);
        $('#section_id').html(options);
        if (selected) $('#section_id').val(selected);
    });
}

// Edit User
const editUrlTemplate = "{{ url('planning/user-management/edit') }}/";

$('#usersTable').on('click', '.edit', function() {
    const id = $(this).data('id');
    $.get(editUrlTemplate + id, function(user) {
        $('#user_id').val(user.id);
        $('#first_name').val(user.first_name);
        $('#middle_name').val(user.middle_name);
        $('#last_name').val(user.last_name);
        $('#extension_name').val(user.extension_name);
        $('#division_id').val(user.division_id);
        loadSections(user.division_id, user.section_id);
        new bootstrap.Modal(document.getElementById('userModal')).show();
    });
});

// Update User
$('#userForm').submit(function(e) {
    e.preventDefault();
    const id = $('#user_id').val();
    $.ajax({
        url: `/planning/user-management/update/${id}`,
        type: 'POST',
        data: $(this).serialize(),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(res) {
            toastr.success(res.success);
            bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
            table.ajax.reload(null, false);
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors;
            if(errors) $.each(errors, (_, v) => toastr.error(v[0]));
            else toastr.error('Something went wrong!');
        }
    });
});

// Load Sections on Division Change
$('#division_id').on('change', function() { loadSections($(this).val()); });

// Activate / Deactivate
// Show Deactivate Modal
$('#usersTable').on('click', '.toggle-status', function() {
    const id = $(this).data('id');
    const isDeactivate = $(this).text().trim() === 'Deactivate';

    if(isDeactivate){
        $('#deactivate_user_id').val(id);
        $('#reason').val('');
        new bootstrap.Modal(document.getElementById('deactivateModal')).show();
    } else {
        // Activate directly
        $.ajax({
            url: `/planning/user-management/activate/${id}`,
            type: 'PATCH',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){ toastr.success(res.success); table.ajax.reload(null,false); },
            error: function(xhr){ toastr.error(xhr.responseJSON?.message ?? 'Action failed.'); }
        });
    }
});

// Submit Deactivation
$('#deactivateForm').submit(function(e){
    e.preventDefault();
    const id = $('#deactivate_user_id').val();
    const reason = $('#reason').val();

    if(!reason.trim()){
        toastr.warning('Reason is required.');
        return;
    }

    $.ajax({
        url: `/planning/user-management/deactivate/${id}`,
        type: 'PATCH',
        data: { reason: reason },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(res){
            toastr.success(res.success);
            bootstrap.Modal.getInstance(document.getElementById('deactivateModal')).hide();
            table.ajax.reload(null,false);
        },
        error: function(xhr){
            toastr.error(xhr.responseJSON?.message ?? 'Action failed.');
        }
    });
});

</script>
@endpush
