@extends('layouts.app')

@section('content')
<div class="container">
    <div id="calendar"></div>
</div>

<!-- Modal for Adding Event -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="eventForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="date_of_event" id="event_date">
          <div class="mb-3">
            <label>Event Name</label>
            <input type="text" name="event_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contact Person</label>
            <input type="text" name="contact_person_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contact Phone</label>
            <input type="text" name="contact_person_phone" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Event</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        selectable: true,
        events: '{{ route('calendar.fetch') }}',
        dateClick: function(info) {
            // Open modal and set date
            document.getElementById('event_date').value = info.dateStr;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }
    });

    calendar.render();

    // Handle form submission
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let form = e.target;
        let data = new FormData(form);

        fetch('{{ route('events.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: data
        })
        .then(res => res.json())
        .then(res => {
            bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
            calendar.refetchEvents();
            alert('Event added successfully');
        })
        .catch(err => {
            alert('Error saving event');
        });
    });
});
</script>
@endpush
