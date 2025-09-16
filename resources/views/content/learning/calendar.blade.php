@extends('layouts/contentNavbarLayout')

@section('title', 'Calendar')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ✅ FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet" /> //

<style>
  #calendar .fc-daygrid-day-number {
    color: #d32f2f;
    font-weight: bold;
  }
</style>

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="fw-bold">Calendar of Events</h2>
    </div>
    <div id="calendar" style="min-height: 600px;"></div>
  </div>
</div>
<!-- View Event Modal -->
<div id="viewEventModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:white; padding:20px; border-radius:8px; width:400px; position:relative;">
    <h5>Event Details</h5>
    <div id="eventDetails">
      <p><strong>Title:</strong> <span id="modalTitle"></span></p>
      <p><strong>Type:</strong> <span id="modalType"></span></p>
      <p><strong>Start Date:</strong> <span id="modalStart"></span></p>
      <p><strong>End Date:</strong> <span id="modalEnd"></span></p>
      <p><strong>Status:</strong> <span id="modalStatus"></span></p>
    </div>
    <div class="text-end mt-3">
      <button id="closeModal" class="btn btn-secondary">Close</button>
    </div>
  </div>
</div>

<!-- ✅ FullCalendar Scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales-all.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'en',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      editable: false,
      eventSources: [{
        url: '{{ route("learning.calendar.events") }}', // Laravel route
        method: 'GET',
        failure: () => {
          alert('There was an error while fetching events.');
        },
      }],
      eventClick: function(info) {
        const event = info.event.extendedProps;

        document.getElementById('modalTitle').innerText = info.event.title;
        document.getElementById('modalType').innerText = event.type || 'N/A';
        document.getElementById('modalStart').innerText = info.event.startStr;
        document.getElementById('modalEnd').innerText = info.event.endStr || 'N/A';
        document.getElementById('modalStatus').innerText = event.status || 'N/A';

        document.getElementById('viewEventModal').style.display = 'flex';
      },
    });

    calendar.render();
  });
  document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('viewEventModal').style.display = 'none';
  });

  window.addEventListener('click', function(e) {
    const modal = document.getElementById('viewEventModal');
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>
@endsection
