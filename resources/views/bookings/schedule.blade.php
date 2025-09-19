@extends('layouts.app')

@section('content')
<div class="section">
    <div class="toolbar">
        <div class="left">
            <button class="pill" id="btn-prev" title="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="pill" id="btn-today" title="Today">
                <i class="fas fa-calendar-day"></i> Today
            </button>
            <button class="pill" id="btn-next" title="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="right">
            <span class="muted">View</span>
            <button class="pill active" id="view-month" title="Monthly view">
                <i class="fas fa-calendar-alt"></i> Month
            </button>
            <button class="pill" id="view-week" title="Weekly view">
                <i class="fas fa-calendar-week"></i> Week
            </button>
            <button class="pill" id="view-day" title="Daily view">
                <i class="fas fa-calendar-day"></i> Day
            </button>
        </div>
    </div>
    <div id='calendar'></div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: false,
            height: 'auto',
            events: '{{ route('bookings.getEvents') }}',
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
            eventDidMount: function(info) {
                if (info.event.extendedProps && info.event.extendedProps.payment_status) {
                    var status = info.event.extendedProps.payment_status;
                    info.el.style.borderLeft = '4px solid ' + (status === 'paid' ? '#28a745' : '#ffc107');
                }
            },
        });
        calendar.render();

        function setActive(button) {
            document.querySelectorAll('.toolbar .pill').forEach(function(b){ b.classList.remove('active'); });
            button.classList.add('active');
        }

        document.getElementById('btn-prev').addEventListener('click', function(){ calendar.prev(); });
        document.getElementById('btn-next').addEventListener('click', function(){ calendar.next(); });
        document.getElementById('btn-today').addEventListener('click', function(){ calendar.today(); });

        document.getElementById('view-month').addEventListener('click', function(e){ calendar.changeView('dayGridMonth'); setActive(e.target); });
        document.getElementById('view-week').addEventListener('click', function(e){ calendar.changeView('timeGridWeek'); setActive(e.target); });
        document.getElementById('view-day').addEventListener('click', function(e){ calendar.changeView('timeGridDay'); setActive(e.target); });
    });
</script>
@endpush
