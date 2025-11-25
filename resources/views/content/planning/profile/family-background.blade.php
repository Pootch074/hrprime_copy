@extends('layouts/contentNavbarLayout')

@section('title', 'Family Background')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card shadow-sm">
  <div class="card-header">
    <h5 class="mb-0">Family Background</h5>
  </div>
  <div class="card-body">
    <form id="familyBackgroundForm">

        {{-- Spouse --}}
        <input type="text" name="s_lname" class="form-control">
        <input type="text" name="s_fname" class="form-control">
        <input type="text" name="s_mname" class="form-control">
        <input type="text" name="s_ext" class="form-control">

        <input type="text" name="s_occ" class="form-control">
        <input type="text" name="s_emp" class="form-control">
        <input type="text" name="s_addr" class="form-control">

        {{-- Father --}}
        <input type="text" name="f_lname" class="form-control">
        <input type="text" name="f_fname" class="form-control">
        <input type="text" name="f_mname" class="form-control">
        <input type="text" name="f_ext" class="form-control">

        {{-- Mother --}}
        <input type="text" name="m_lname" class="form-control">
        <input type="text" name="m_fname" class="form-control">
        <input type="text" name="m_mname" class="form-control">
        <input type="text" name="m_ext" class="form-control">

      {{-- Children --}}
      <h6 class="fw-bold">Children</h6>
      <div id="children-wrapper"></div>
      <div class="mb-3">
        <button type="button" id="addChildBtn" class="btn btn-outline-primary btn-sm">
          + Add Child
        </button>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    let childIndex = 0;

    // Add child fields
    $('#addChildBtn').on('click', function() {
        childIndex++;
        $('#children-wrapper').append(`
            <div class="row g-2 mb-2 child-row" id="child-${childIndex}">
                <div class="col-md-4">
                    <input type="text" name="children[${childIndex}][last_name]" class="form-control" placeholder="Last Name">
                </div>
                <div class="col-md-4">
                    <input type="text" name="children[${childIndex}][first_name]" class="form-control" placeholder="First Name">
                </div>
                <div class="col-md-3">
                    <input type="text" name="children[${childIndex}][middle_name]" class="form-control" placeholder="Middle Name">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-child" data-id="${childIndex}">X</button>
                </div>
            </div>
        `);
    });

    // Remove child row
    $(document).on('click', '.remove-child', function() {
        let id = $(this).data('id');
        $('#child-' + id).remove();
    });

    // Submit form
    $('#familyBackgroundForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('planning.profile.family-background.store', $employee->id) }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                toastr.success('Family background saved successfully!');
                $('#children-wrapper').empty(); // clear children after save
            },
            error: function(xhr) {
                toastr.error('Something went wrong. Please try again.');
            }
        });
    });
});
</script>
@endsection
