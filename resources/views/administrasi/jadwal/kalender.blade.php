@extends('administrasi.layouts.header')

@section('title', 'Kalender Jadwal')

@section('content')
<style>
    .page-header-kal {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(14, 165, 233, 0.25);
        position: relative;
        overflow: hidden;
    }
    .page-header-kal::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header-kal .content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }
    .page-header-kal h1 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0; }
    .page-header-kal p { margin: 0; font-size: 0.82rem; opacity: 0.95; }
    .btn-glass {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.25s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-glass:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: translateY(-2px);
    }

    .calendar-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .calendar-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .calendar-card-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .calendar-card-header h5 i { color: #0284c7; }
    .calendar-card-body { padding: 20px; }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 100px; height: 100px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0f2fe, #cffafe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        font-size: 2.5rem;
    }

    /* Custom FullCalendar */
    #calendar {
        min-height: 650px;
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    .fc-toolbar-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #1e293b;
    }
    .fc-button {
        background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 14px !important;
        font-weight: 600 !important;
        font-size: 0.82rem !important;
        text-transform: capitalize !important;
        transition: all 0.25s !important;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3) !important;
    }
    .fc-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4) !important;
        opacity: 1;
    }
    .fc-button:disabled {
        opacity: 0.5 !important;
        transform: none !important;
    }
    .fc-button-active {
        background: linear-gradient(135deg, #0284c7, #0369a1) !important;
    }
    .fc-event {
        cursor: pointer;
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 6px;
        border: none !important;
        font-weight: 600;
        transition: all 0.2s;
    }
    .fc-event:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .fc-daygrid-day {
        min-height: 100px !important;
        transition: background 0.2s;
    }
    .fc-daygrid-day:hover {
        background: #f8fafc;
    }
    .fc-day-today {
        background: #eff6ff !important;
    }
    .fc-col-header-cell {
        background: #f8fafc;
        padding: 12px 0 !important;
        font-weight: 700 !important;
        color: #475569 !important;
        text-transform: uppercase;
        font-size: 0.78rem !important;
        letter-spacing: 0.5px;
    }
    .fc-daygrid-day-number {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
    }
</style>

<div class="page-header-kal">
    <div class="content">
        <div>
            <h1>
                <i class="fas fa-calendar-week me-2"></i>
                Kalender Jadwal Pelajaran
            </h1>
            <p>
                <i class="fas fa-info-circle me-1"></i>
                Lihat jadwal pelajaran dalam tampilan kalender
            </p>
        </div>
        <a href="{{ route('administrasi.jadwal.index') }}" class="btn-glass">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="calendar-card">
    <div class="calendar-card-header">
        <h5>
            <i class="fas fa-calendar-alt"></i>
            Tampilan Kalender Jadwal
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
            <i class="fas fa-database me-1"></i> {{ count($events ?? []) }} Jadwal
        </span>
    </div>
    <div class="calendar-card-body">
        @if(isset($events) && count($events) > 0)
            <div id="calendar"></div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h5 class="fw-bold mb-2">Belum ada data jadwal</h5>
                <p class="text-muted mb-3">Silakan tambahkan jadwal terlebih dahulu</p>
                <a href="{{ route('administrasi.jadwal.create') }}" class="btn btn-primary rounded-3">
                    <i class="fas fa-plus me-1"></i> Tambah Jadwal
                </a>
            </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            events: {!! json_encode($events) !!},
            eventClick: function(info) {
                var desc = info.event.extendedProps.description || 'Tidak ada keterangan';
                Swal.fire({
                    title: info.event.title,
                    html: '<div style="text-align:left;font-size:0.9rem;">' + desc + '</div>',
                    icon: 'info',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#0ea5e9'
                });
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            dayMaxEvents: true,
            eventDisplay: 'block'
        });
        calendar.render();
    });
</script>
@endpush
@endsection