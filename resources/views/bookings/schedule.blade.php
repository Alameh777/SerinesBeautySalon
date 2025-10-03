@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex gap-2 align-items-center">
            <button id="btn-prev" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Prev</button>
            <button id="btn-today" class="btn btn-secondary"><i class="fas fa-calendar-day"></i> Today</button>
            <button id="btn-next" class="btn btn-primary"><i class="fas fa-chevron-right"></i> Next</button>
            <input type="date" id="datepicker" class="form-control" style="width:auto;">
        </div>
        <h3 class="mb-0" id="calendar-date">Day View Calendar</h3>
    </div>

    <div id="calendar" class="shadow-sm rounded bg-white p-3"></div>
</div>
@endsection

@push('styles')
<style>
    #calendar {
        font-family: 'Poppins', sans-serif;
        max-width: 100%;
    }

    .fc .fc-toolbar-title {
        font-size: 22px;
        font-weight: 600;
    }

    .fc-event {
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        padding: 2px 6px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-event.paid {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: #fff !important;
    }
    .fc-event.unpaid {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }

    .fc-timegrid-slot {
        height: 40px;
    }

    @media (max-width: 768px) {
        .fc-timegrid-event.fc-event {
            font-size: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const dateTitleEl = document.getElementById('calendar-date');
    const datePicker = document.getElementById('datepicker');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridDay',
        headerToolbar: false,
        height: 'auto',
        slotMinTime: "08:00:00",
        slotMaxTime: "20:00:00",
        allDaySlot: false,
        eventOverlap: true,
        timeZone: 'local',
        events: '{{ route('bookings.getEvents') }}',
        eventDidMount: function(info) {
            if(info.event.extendedProps.payment_status) {
                info.el.classList.add(info.event.extendedProps.payment_status);
            }
        },
        datesSet: function(info) {
            // Update header to show full date
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateTitleEl.textContent = info.start.toLocaleDateString(undefined, options);

            // Update datepicker value
            const yyyy = info.start.getFullYear();
            const mm = String(info.start.getMonth() + 1).padStart(2, '0');
            const dd = String(info.start.getDate()).padStart(2, '0');
            datePicker.value = `${yyyy}-${mm}-${dd}`;
        }
    });

    calendar.render();

    // Navigation buttons
    document.getElementById('btn-prev').addEventListener('click', function(){ calendar.prev(); });
    document.getElementById('btn-next').addEventListener('click', function(){ calendar.next(); });
    document.getElementById('btn-today').addEventListener('click', function(){ calendar.today(); });

    // Date picker: jump to selected day
    datePicker.addEventListener('change', function() {
        if(this.value) {
            calendar.gotoDate(this.value);
        }
    });
});
</script>
@endpush
