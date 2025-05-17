@extends('layouts.app')

@section('head')
<!-- FullCalendar with Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('content')
<div class="container">
    <h2>{{ Auth::user()->organization->name }}</h2>
    <p>Welcome, {{ Auth::user()->name ?? Auth::user()->email }}!</p>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    @if(Auth::user()->role_id == 1)
    <div class="row">

        {{-- Manage Organization Users --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Organization Users</h5>
                    <p class="card-text">View and manage users from your organization.</p>
                    <a href="{{ route('organization.users.index') }}" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        {{-- Manage Organization Details --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Organization Details</h5>
                    <p class="card-text">Update Organizatio's Profile & Settings</p>
                    <a href="{{ route('organization.details.edit')}}" class="btn btn-primary">Edit Organization Details</a>
                </div>
            </div>
        </div>

        {{-- Generate Reports --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Generate Reports</h5>
                    <p class="card-text">Generate and view Performance and Activity Report</p>
                    <a href="{{ route('organization.reports.index') }}" class="btn btn-primary">Generate Reports</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container mt-4">
        <h4 class="mb-3">Calendar</h4>
    <div id="calendar"></div>
</div>

</div>


<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="eventForm">
        <div class="modal-header">
          <h5 class="modal-title">Add New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3">

            <input type="hidden" name="date_of_event" id="eventDate">

            <div class="col-md-6">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" class="form-control @error('event_name') is-invalid @enderror" id="event_name" name="event_name" required>
                @error('event_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="col-md-12">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
            </div>

            <div class="col-md-6">
                <label for="address_location" class="form-label">Location</label>
                <input type="text" class="form-control" id="address_location" name="address_location">
            </div>

            <div class="col-md-6 form-check mt-3">
                <input class="form-check-input" type="checkbox" value="0" name="is_adv_paid" id="is_adv_paid">
                <label class="form-check-label" for="is_adv_paid">Advance Paid</label>
            </div>

            <div class="col-md-6">
                <label for="adv_amt" class="form-label">Advance Amount</label>
                <input type="number" class="form-control" id="adv_amt" name="adv_amt">
            </div>

            <div class="col-md-6 form-check mt-3">
                <input class="form-check-input" type="checkbox" value="0" name="is_ret_paid" id="is_ret_paid">
                <label class="form-check-label" for="is_ret_paid">Return Paid</label>
            </div>

            <div class="col-md-6">
                <label for="ret_amt" class="form-label">Return Amount</label>
                <input type="number" class="form-control" id="ret_amt" name="ret_amt">
            </div>

            <div class="col-md-6">
                <label for="contact_person_name" class="form-label">Contact Person</label>
                <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" required>
            </div>

            <div class="col-md-6">
                <label for="contact_person_phone" class="form-label">Contact Phone</label>
                <input type="text" class="form-control" id="contact_person_phone" name="contact_person_phone">
            </div>

            <div class="col-md-12">
                <label class="form-label">Misc Spend</label>
                <div id="misc-items-container"></div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addMiscRow()">+ Add Misc Item</button>
            </div>


        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Event</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function()
        {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl,
            {
                themeSystem: 'bootstrap5',
                initialView: 'dayGridMonth',
                // height: 650,
                // headerToolbar: {
                //     left: 'prev,next today',
                //     center: 'title',
                //     right: 'dayGridMonth,timeGridWeek,timeGridDay',
                // }

                selectable: true,
                events: '{{ route('events.index') }}',
                select: function(info)
                {
                    // set the hidden date field
                    document.getElementById('eventDate').value = info.startStr;

                    // reset the form
                    document.getElementById('eventForm').reset();

                    // show the modal
                    var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    myModal.show();
                },

                eventDidMount: function(info)
                {
                    const tooltipContent =
                    `   <strong>${info.event.extendedProps.event_name}</strong><br>
                        Address: ${info.event.extendedProps.address || 'N/A'}<br>
                        Contact: ${info.event.extendedProps.contact_person_name || 'N/A'}<br>
                        Phone: ${info.event.extendedProps.contact_person_phone || 'N/A'}<br>
                        Is_Advance_Paid: ${info.event.extendedProps.is_adv_paid ? 'Yes' : 'No'}<br>
                        Advance: ${info.event.extendedProps.is_adv_paid ? info.event.extendedProps.adv_amt : '0'}<br>
                        Is_Return_Paid: ${info.event.extendedProps.is_ret_paid ? 'Yes' : 'No'}<br>
                        Return: ${info.event.extendedProps.is_ret_paid ? info.event.extendedProps.ret_amt : '0'}
                    `;

                    info.el.setAttribute('data-bs-toggle', 'tooltip');
                    info.el.setAttribute('data-bs-html', 'true');
                    info.el.setAttribute('title', tooltipContent);
                    info.el.style.marginBottom = '1px';
                    new bootstrap.Tooltip(info.el);
                },

                eventClick: function(info)
                {
                    const event = info.event;
                    const props = event.extendedProps;

                    // model with data
                    document.getElementById('eventDate').value = event.startStr;
                    document.getElementById('event_name').value = props.event_name;
                    document.getElementById('address').value = props.address;
                    document.getElementById('address_location').value = props.address_location;
                    document.getElementById('is_adv_paid').checked = props.is_adv_paid == 1;
                    document.getElementById('adv_amt').value = props.adv_amt || 0;
                    document.getElementById('is_ret_paid').checked = props.is_ret_paid == 1;
                    document.getElementById('ret_amt').value = props.ret_amt || 0;
                    document.getElementById('contact_person_name').value = props.contact_person_name;
                    document.getElementById('contact_person_phone').value = props.contact_person_phone;

                    // misc items
                    document.getElementById('misc-items-container').innerHTML = '';
                    if(props.misc_spend)
                    {
                        let misc = props.misc_spend;
                         // Parse only if it's a string
                        if (typeof misc === 'string') {
                            try {
                                misc = JSON.parse(misc);
                            } catch (e) {
                                console.warn('Invalid misc_spend JSON:', misc);
                                misc = null;
                            }
                        }

                        if (misc && typeof misc === 'object') {
                            for (const [key, value] of Object.entries(misc)) {
                                addMiscRow(key, value);
                            }
                        }
                    }
                    document.getElementById('eventForm').dataset.eventId = event.id;
                    var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    myModal.show();
                },

                // events: '/calendar-events' // Laravel route to fetch events
            });

            calendar.render();

            document.getElementById('eventForm').addEventListener('submit', function(e)
            {
                e.preventDefault();
                let form = e.target;
                let formdata = new FormData(form);
                let data = {};

                formdata.forEach(function(value, key)
                {
                    if (key === 'is_adv_paid' || key === 'is_ret_paid')
                        data[key] = form.querySelector(`[name="${key}"]`).checked ? 1 : 0;
                    else if (key !== 'misc_key[]' && key !== 'misc_value[]')
                        data[key] = value;
                });

                let miscKeys = formdata.getAll('misc_key[]');
                let miscValues = formdata.getAll('misc_value[]');
                let miscObjects = {};

                miscKeys.forEach((key, index) => {
                    if (key.trim())
                        miscObjects[key] = parseFloat(miscValues[index] || 0);
                });

                data.misc_spend = JSON.stringify(miscObjects);
                data.org_id = {{ Auth::guard('web')->user()->org_id }};
                data.added_by = {{ Auth::guard('web')->user()->id }};
                data.updated_by = {{ Auth::guard('web')->user()->id }};

                const eventId = form.dataset.eventId;
                const method = eventId ? 'PUT' : 'POST';
                const url = eventId ? `/calendar-events/${eventId}` : `{{ route('events.store') }}`;

                fetch(url, {
                    method : method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    calendar.refetchEvents();
                    alert(eventId ? 'Event updated successfully' : 'Event created successfully');
                    form.removeAttribute('data-event-id');
                });

            });

        });

        function addMiscRow(key = '', value = '')
        {
            const container = document.getElementById('misc-items-container');
            const row = document.createElement('div');
            row.classList.add('row', 'mb-2');

            row.innerHTML = `
                <div class="col-5">
                    <input type="text" class="form-control" placeholder="Item" name="misc_key[]" value="${key}">
                </div>
                <div class="col-5">
                    <input type="number" class="form-control" placeholder="Amount" name="misc_value[]" value="${value}">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">X</button>
                </div>
            `;
            container.appendChild(row);
        }

        setTimeout(() =>
        {
            const alert = document.querySelectorAll('.alert-success');
            alert.forEach(alert => {
                alert.classList.remove('show')
                alert.classList.add('fade')
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000); // 5 seconds

    </script>

@endsection