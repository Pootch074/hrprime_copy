@extends('layouts/contentNavbarLayout')

@section('title', 'Events')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">List of Scholarship</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Scholarship</button>
    </div>

    <div class="table-responsive">
      <table id="employeeTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Scholarship Title</th>
            <th>Date of Submission</th>
            <th>Status</th>
            <th>View Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($scholarships as $index => $scholarship)
          <tr>
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $scholarship->title }}</td>
            <td>{{ $scholarship->date_from }}</td>
            <td>{{ $scholarship->status ?? 'Pending' }}</td>
            <td>
              <button
                class="btn btn-sm btn-primary view-btn"
                data-id="{{ $scholarship->id }}"
                data-title="{{ $scholarship->title }}"
                data-date="{{ $scholarship->date_from }}"
                data-status="{{ $scholarship->status }}">View</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
      <div style="background:white; padding:20px; border-radius:8px; width:400px; position:relative;">
        <h5>Add New Scholarship</h5>
        <form id="eventForm">
          <div class="mb-2">
            <label>Scholarship Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Date of Final Submission</label>
            <input type="date" name="dateFrom" class="form-control" required>
          </div>
          <input type="hidden" name="addedBy" value="{{ Auth::user()->name }}">
          <input type="hidden" name="dateAdded" value="{{ now()->toDateString() }}">
          <div class="text-end">
            <button type="button" id="closeModalBtn" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-success">Add Scholarship</button>
          </div>
        </form>
      </div>
    </div>

    <!-- View/Edit Modal -->
    <div id="viewModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
      <div style="background:white; padding:20px; border-radius:8px; width:400px; position:relative;">
        <h5>Scholarship Details</h5>
        <form id="viewForm">
          <input type="hidden" name="id" id="view-id">
          <div class="mb-2">
            <label>Title</label>
            <input type="text" id="view-title" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Date of Submission</label>
            <input type="date" id="view-date" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Status</label>
            <select id="view-status" name="status" class="form-control">
              <option value="Pending">Pending</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="text-end">
            <button type="button" id="closeViewModalBtn" class="btn btn-secondary me-2">Close</button>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </div>
        </form>
      </div>
    </div>


  </div>
</div>

@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  const viewModal = document.getElementById('viewModal');
  const closeViewModalBtn = document.getElementById('closeViewModalBtn');
  const viewForm = document.getElementById('viewForm');

  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('view-id').value = btn.dataset.id;
      document.getElementById('view-title').value = btn.dataset.title;
      document.getElementById('view-date').value = btn.dataset.date;
      document.getElementById('view-status').value = btn.dataset.status;
      viewModal.style.display = 'flex';
    });
  });

  closeViewModalBtn.addEventListener('click', () => {
    viewModal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === viewModal) {
      viewModal.style.display = 'none';
    }
  });

  viewForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('view-id').value;
    const status = document.getElementById('view-status').value;

    fetch(`/scholarships/${id}/status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          status
        })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => {
        console.error(err);
        alert("Failed to update status.");
      });
  });
</script>
<script>
  const openModalBtn = document.getElementById('openModalBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const eventModal = document.getElementById('eventModal');
  const eventForm = document.getElementById('eventForm');

  openModalBtn.addEventListener('click', () => {
    eventModal.style.display = 'flex';
  });

  closeModalBtn.addEventListener('click', () => {
    eventModal.style.display = 'none';
    eventForm.reset();
  });

  window.addEventListener('click', (e) => {
    if (e.target === eventModal) {
      eventModal.style.display = 'none';
      eventForm.reset();
    }
  });

  eventForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("scholarships.store") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload(); // Refresh to show new row
      })
      .catch(err => {
        console.error(err);
        alert("Something went wrong!");
      });
  });
</script>
<script>
  jQuery(function($) {
    $('#employeeTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endpush
