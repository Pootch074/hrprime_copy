@extends('layouts/contentNavbarLayout')

@section('title', 'Events')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">List of Nominees</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Event</button>
    </div>

    <div class="table-responsive">
      <table id="employeeTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Event Title</th>
            <th>Type of Event</th>
            <th>Date From</th>
            <th>Date To</th>
            <th>Status</th>
            <th>View Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($events as $index => $event)
          <tr>
            <td>{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $event->title }}</td>
            <td>{{ $event->type }}</td>
            <td>{{ $event->date_from }}</td>
            <td>{{ $event->date_to }}</td>
            <td>{{ $event->status }}</td>
            <td>
              <button
                class="btn btn-sm btn-primary view-btn"
                data-id="{{ $event->id }}"
                data-title="{{ $event->title }}"
                data-type="{{ $event->type }}"
                data-date-from="{{ $event->date_from }}"
                data-date-to="{{ $event->date_to }}"
                data-status="{{ $event->status }}">View</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div id="eventModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
      <div style="background:white; padding:20px; border-radius:8px; width:400px; position:relative;">
        <h5>Add New Event</h5>
        <form id="eventForm">
          <div class="mb-2">
            <label>Event Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Type of Event</label>
            <select name="type" class="form-control" required>
              <option disabled selected>Select event type</option>
              <option value="Seminar">Seminar</option>
              <option value="Training">Training</option>
              <option value="Orientation">Orientation</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="mb-2">
            <label>Date From</label>
            <input type="date" name="dateFrom" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Date To</label>
            <input type="date" name="dateTo" class="form-control" required>
          </div>
          <div hidden class="mb-2">
            <label>Status</label>
            <input type="text" name="status" class="form-control" value='Active'>
          </div>
          <input type="hidden" name="addedBy" value="{{ Auth::user()->name }}">
          <input type="hidden" name="dateAdded" value="{{ now()->toDateString() }}">
          <div class="text-end">
            <button type="button" id="closeModalBtn" class="btn btn-secondary me-2">Cancel</button>
            <button type="submit" class="btn btn-success">Add Event</button>
          </div>
        </form>
      </div>
    </div>
    <!-- View/Edit Event Modal -->
    <div id="viewModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
      <div style="background:white; padding:20px; border-radius:8px; width:400px; position:relative;">
        <h5>Event Details</h5>
        <form id="viewForm">
          <input type="hidden" id="view-id" name="id">
          <div class="mb-2">
            <label>Event Title</label>
            <input type="text" id="view-title" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Type of Event</label>
            <input type="text" id="view-type" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Date From</label>
            <input type="date" id="view-date-from" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Date To</label>
            <input type="date" id="view-date-to" class="form-control" readonly>
          </div>
          <div class="mb-2">
            <label>Status</label>
            <select id="view-status" name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
              <option value="Cancelled">Cancelled</option>
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

    fetch('{{ route("events.store") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => {
        console.error(err);
        alert("Something went wrong!");
      });
  });
</script>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  const viewModal = document.getElementById('viewModal');
  const closeViewModalBtn = document.getElementById('closeViewModalBtn');
  const viewForm = document.getElementById('viewForm');

  document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('view-id').value = button.dataset.id;
      document.getElementById('view-title').value = button.dataset.title;
      document.getElementById('view-type').value = button.dataset.type;
      document.getElementById('view-date-from').value = button.dataset.dateFrom;
      document.getElementById('view-date-to').value = button.dataset.dateTo;
      document.getElementById('view-status').value = button.dataset.status;

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

    fetch(`/events/${id}/status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
  jQuery(function($) {
    $('#employeeTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>
@endpush