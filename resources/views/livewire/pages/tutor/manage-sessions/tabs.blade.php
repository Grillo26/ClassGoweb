<ul class="am-tabs-nav">
    <li class="am-tab-item @if($activeRoute == 'tutor.bookings.subjects') am-tab-active @endif">
        <a href="{{ route('tutor.bookings.subjects') }}" wire:navigate.remove
            class="am-tab-link @if($activeRoute == 'tutor.bookings.subjects') am-tab-active @endif">
            {{__('subject.subject_title') }}
        </a>
    </li>
    <li
        class="am-tab-item @if(in_array($activeRoute, ['tutor.bookings.manage-sessions','tutor.bookings.session-detail'])) am-tab-active @endif">
        <a href="{{ route('tutor.bookings.manage-sessions') }}" wire:navigate.remove
            class="am-tab-link @if(in_array($activeRoute, ['tutor.bookings.manage-sessions','tutor.bookings.session-detail'])) am-tab-active @endif">
            {{__('calendar.title') }}
        </a>
    </li>
    <li class="am-tab-item @if($activeRoute == 'tutor.bookings.upcoming-bookings') am-tab-active @endif">
        <a href="{{ route('tutor.bookings.upcoming-bookings') }}" wire:navigate.remove
            class="am-tab-link @if($activeRoute == 'tutor.bookings.upcoming-bookings') am-tab-active @endif">
            {{ __('calendar.upcoming_bookings') }}
        </a>
    </li>
</ul>


@push('styles')

<link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/manage-sessions/components/tabs.css') }}">


@endpush