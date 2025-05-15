@extends('layouts.admin-app')

@section('content')
<livewire:admin.tutorias-table />
@endsection

@push('styles')
<style>
.select-estado {
    font-weight: bold;
    border-radius: 6px;
    padding: 4px 12px;
    border: 1px solid #e0e0e0;
    min-width: 120px;
    transition: background 0.2s, color 0.2s;
}
.select-estado.pendiente   { background: #fff7e0; color: #FE9C30; }
.select-estado.aceptado    { background: #eafbe7; color: #2B9C0E; }
.select-estado.rechazado   { background: #ffeaea; color: #ff0000; }
.select-estado.completado  { background: #e6f7fa; color: #56C5DE; }
.select-estado.no_completado { background: #f0f0f0; color: #160e5a; }
.badge-estado {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 2px 12px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 13px;
    text-align: center;
    height: 24px;
    line-height: 1;
    box-shadow: none;
    border: none;
    vertical-align: middle;
}
.estado-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 0px;
    margin-top: 0px;
}
/* Centrar verticalmente la celda de estado */
td[data-label="Estado"] {
    vertical-align: middle !important;
    height: 48px;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}
.estado-dot-pendiente      { background: #FE9C30; }
.estado-dot-aceptado       { background: #2B9C0E; }
.estado-dot-rechazado      { background: #ff0000; }
.estado-dot-completado     { background: #00f66f; }
.estado-dot-no_completado  { background: #fe7b00; }
.badge-pendiente      { background: #fff7e0; color: #FE9C30; }
.badge-aceptado       { background: #eafbe7; color: #2B9C0E; }
.badge-rechazado      { background: #ffeaea; color: #ff0000; }
.badge-completado     { background: #e6f7fa; color: #56C5DE; }
.badge-no_completado  { background: #f0f0f0; color: #888; }
</style>
@endpush

@push('scripts')
<script>
window.addEventListener('modal-debug', event => {
    console.log('Livewire modal-debug:', event.detail);
});
</script>
@endpush 