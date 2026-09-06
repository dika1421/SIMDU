@extends('administrasi.layouts.header')

@section('title', 'Kalender Jadwal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-calendar-week me-2"></i>
        Kalender Jadwal Pelajaran
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
</div>
@endif

<div class="card">
    <div class="card-body">
        @if(isset($events) && count($events) > 0)
            <div id="calendar"></div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada data jadwal. Silakan tambahkan jadwal terlebih dahulu.
                <br>
                <a href="{{ route('administrasi.jadwal.create') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </a>
            </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    #calendar {
        min-height: 600px;
    }
    .fc-event {
        cursor: pointer;
        font-size: 12px;
        padding: 2px 4px;
    }
    .fc-daygrid-day {
        min-height: 80px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: {!! json_encode($events) !!},
            eventClick: function(info) {
                var desc = info.event.extendedProps.description || 'Tidak ada keterangan';
                Swal.fire({
                    title: info.event.title,
                    text: desc,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }
        });
        calendar.render();
    });
</script>
@endpush
@endsection