@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.requirements.title'))
@section('container')
    <p class="paragraph" style="text-align: center;">{!! trans('installer_messages.requirements.message') !!}</p>
    <ul class="list">
        <li class="list__item {{ $phpSupportInfo['supported'] ? 'success' : 'error' }}">PHP Version >= {{ $phpSupportInfo['minimum'] }}</li>
        <li class="list__item {{ $maxExecutionTime['supported'] ? 'success' : 'error' }}">Max Execution Time >= {{ $maxExecutionTime['minimum'] }}</li>

        @foreach($requirements['requirements'] as $extention => $enabled)
            <li class="list__item {{ $enabled ? 'success' : 'error' }}">{{ $extention }}</li>
        @endforeach
    </ul>

    @if ( ! isset($requirements['errors']) && $phpSupportInfo['supported'] == 'success')
        <div class="buttons">
            <a class="button" href="{{ route('LaravelInstaller::permissions') }}">
                {{ trans('installer_messages.next') }}
            </a>
        </div>
    @endif
@stop
