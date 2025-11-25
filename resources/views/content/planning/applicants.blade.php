@extends('layouts/contentNavbarLayout')

@section('title', 'Applicant')

@section('content')

@php
    $statusColors = [
        'Pending' => '#ffc107',
        'Examination' => '#17a2b8',
        'Deliberation' => '#6610f2',
        'Hired' => '#28a745',
        'Not Hired' => '#dc3545',
        'Submission of Requirements' => '#6c757d',
        'On-Boarding' => '#007bff',
    ];
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Applicants</h4>
      <button id="openModalBtn" class="btn btn-success">Add Applicant</button>
    </div>

    <div class="table-responsive">
      <table id="applicantTable" class="table">
      <thead class="table-light">
        <tr>
          <th style="width: 10%;">Item Number</th>
          <th style="width: 10%;">Applicant No.</th>
          <th style="width: 25%;">Applicant's Name</th>
          <th style="width: 10%;">Date Applied</th>
          <th style="width: 10%;">Status</th>
          <th style="width: 10%;">Date Hired</th>
          <th style="width: 15%;">Action</th>
        </tr>
      </thead>
        <tbody>
          @foreach($applicants as $applicant)
          <tr data-id="{{ $applicant->id }}">
            <td>{{ $applicant->item_number }}</td>
            <td>{{ $applicant->applicant_no }}</td>
            <td>
                {{ $applicant->first_name }}
                @if($applicant->middle_name) {{ $applicant->middle_name }} @endif
                {{ $applicant->last_name }}
                @if($applicant->extension_name) {{ $applicant->extension_name }} @endif
            </td>
            <td>{{ $applicant->date_applied }}</td>
            <td>
              @php $color = $statusColors[$applicant->status] ?? '#999'; @endphp
              <span class="px-2 py-1 text-white"
                    style="background-color: {{ $color }}; border-radius: .5rem; display:inline-block;">
                {{ $applicant->status }}
              </span>
            </td>
            <td>{{ $applicant->date_hired }}</td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn"
                data-id="{{ $applicant->id }}"
                data-item_number="{{ $applicant->item_number }}"
                data-applicant_no="{{ $applicant->applicant_no }}"
                data-first_name="{{ $applicant->first_name }}"
                data-middle_name="{{ $applicant->middle_name }}"
                data-last_name="{{ $applicant->last_name }}"
                data-extension_name="{{ $applicant->extension_name }}"
                data-sex="{{ $applicant->sex }}"
                data-date_of_birth="{{ $applicant->date_of_birth }}"
                data-date_applied="{{ $applicant->date_applied }}"
                data-status="{{ $applicant->status }}"
                data-remarks="{{ $applicant->remarks }}"
                data-date_hired="{{ $applicant->date_hired }}">
                Edit
              </button>
             <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $applicant->id }}" data-url="{{ route('applicants.archive', $applicant->id) }}">Archive</button>

            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="applicantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add Applicant</h5>
      <form id="applicantForm">
        <div class="modal-body row">
          <div class="col-md-6 mb-3">
            <label>Item Number</label>
            <input type="text" name="item_number" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Applicant No.</label>
           <input type="text"
              class="form-control"
              name="applicant_no"
              value="{{ old('applicant_no', $generatedApplicantNo) }}"
              readonly required
              style="text-transform: uppercase; background-color: #e9ecef; cursor: not-allowed;">
          </div>
          <div class="col-md-6 mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Middle Name</label>
            <input type="text" name="middle_name" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Extension Name</label>
            <select name="sex" class="form-control">
              <option value=""></option>
              <option value="JR">JR</option>
              <option value="JR">SR</option>
              <option value="I">I</option>
              <option value="II">II</option>
              <option value="III">III</option>
              <option value="IV">IV</option>
              <option value="V">V</option>
              <option value="VI">VI</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label>Sex</label>
            <select name="sex" class="form-control" required>
              <option value=""></option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Date Applied</label>
            <input type="date" name="date_applied" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value=""></option>
              <option>Pending</option>
              <option>Examination</option>
              <option>Deliberation</option>
              <option>Hired</option>
              <option>Not Hired</option>
              <option>Submission of Requirements</option>
              <option>On-Boarding</option>
            </select>
          </div>
          <div class="col-md-12 mb-3">
            <label>Remarks</label>
            <input type="text" name="remarks" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label>Date Hired</label>
            <input type="date" name="date_hired" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this applicant?
      </div>
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
    $('#applicantForm')[0].reset();
    var modal = new bootstrap.Modal(document.getElementById('applicantModal'));
    modal.show();
  });

$('#applicantForm').submit(function(e) {
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        url: '{{ route("applicants.store") }}',
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Applicant added successfully! Applicant No: ' + response.applicant_no);
                var modal = bootstrap.Modal.getInstance(document.getElementById('applicantModal'));
                modal.hide();
                setTimeout(() => location.reload(), 500);
            } else {
                toastr.error("Failed to add applicant.");
            }
        },
        error: function(xhr) {
            toastr.error("Something went wrong: " + xhr.responseText);
        }
    });
});
$(document).on('click', '.delete-btn', function () {
    deleteId = $(this).data('id'); 
    deleteUrl = $(this).data('url'); // get URL from button
    var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
});

$('#confirmDeleteBtn').click(function() {
    if (!deleteId) return;

    $.ajax({
        url: deleteUrl,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Applicant archived successfully!');
                var modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                modal.hide();
                setTimeout(() => location.reload(), 500);
            } else {
                toastr.error("Failed to archive applicant.");
            }
        },
        error: function(xhr) {
            toastr.error("Something went wrong: " + xhr.responseText);
        }
    });
});

  $('#applicantTable').DataTable();
  
  //Status Color Coding
  document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('status');
  const updateColor = () => {
    const color = select.options[select.selectedIndex].dataset.color;
    select.style.backgroundColor = color;
    select.style.color = '#fff';
    select.style.borderRadius = '0.5rem'; // rectangular with rounded corners
    select.style.padding = '0.5rem';
  };
  select.addEventListener('change', updateColor);
  updateColor(); // initial
});
</script>
@endpush
