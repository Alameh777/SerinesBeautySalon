@extends('layouts.app')

@section('content')
<div class="container my-5">

    <!-- Navigation row -->
    <div class="d-flex flex-wrap gap-2 mb-2 align-items-center">
        <button id="btn-prev" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Prev</button>
        <button id="btn-today" class="btn btn-secondary"><i class="fas fa-calendar-day"></i> Today</button>
        <button id="btn-next" class="btn btn-primary"><i class="fas fa-chevron-right"></i> Next</button>
        <input type="date" id="datepicker" class="form-control" style="width:auto;">
    </div>

    <!-- View buttons row -->
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-outline-secondary view-btn active" data-view="timeGridDay">Day</button>
        <button class="btn btn-outline-secondary view-btn" data-view="timeGridWeek">Week</button>
        <button class="btn btn-outline-secondary view-btn" data-view="dayGridMonth">Month</button>
    </div>

    <!-- Header showing full date -->
    <h3 class="mb-3" id="calendar-date">Day View Calendar</h3>

    <div id="calendar" class="shadow-sm rounded bg-white p-3"></div>
</div>
@endsection

@push('styles')
<style>
    #calendar { font-family: 'Poppins', sans-serif; max-width: 100%; }
    .fc .fc-toolbar-title { font-size: 22px; font-weight: 600; }
    .fc-event { border-radius: 6px; font-size: 12px; font-weight: 500; padding: 2px 6px; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fc-event.paid { background-color: #28a745 !important; border-color: #28a745 !important; color: #fff !important; }
    .fc-event.unpaid { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #000 !important; }
    .fc-timegrid-slot { height: 40px; }
    .view-btn.active { background-color: #0d6efd !important; color: #fff !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const dateTitleEl = document.getElementById('calendar-date');
    const datePicker = document.getElementById('datepicker');
    const viewButtons = document.querySelectorAll('.view-btn');

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
            if(info.event.extendedProps.payment_status) info.el.classList.add(info.event.extendedProps.payment_status);
        },
        datesSet: function(info) {
            const options = calendar.view.type === 'dayGridMonth'
                ? { year: 'numeric', month: 'long' }
                : { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateTitleEl.textContent = info.start.toLocaleDateString(undefined, options);

            const yyyy = info.start.getFullYear();
            const mm = String(info.start.getMonth() + 1).padStart(2, '0');
            const dd = String(info.start.getDate()).padStart(2, '0');
            datePicker.value = `${yyyy}-${mm}-${dd}`;
        }
    });

    calendar.render();

    document.getElementById('btn-prev').addEventListener('click', () => calendar.prev());
    document.getElementById('btn-next').addEventListener('click', () => calendar.next());
    document.getElementById('btn-today').addEventListener('click', () => calendar.today());
    datePicker.addEventListener('change', function() { if(this.value) calendar.gotoDate(this.value); });

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            calendar.changeView(this.dataset.view);
        });
    });
});
</script>
@endpush
