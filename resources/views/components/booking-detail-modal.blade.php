@props(['currentBooking', 'id'=> 'session-detail'])
<div class="modal fade am-session-detail-modal_two" id="{{ $id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="am-session-detail">
            <div class="am-session-detail_sidebar">
                <div class="am-session-detail_content">
                    <span>
                        <i class="am-icon-book-1"></i>
                    </span>
                    <div class="am-closepopup" data-bs-dismiss="modal">
                        <i class="am-icon-multiply-01"></i>
                    </div>
                </div>
                <ul class="am-session-duration">
                    <li>
                        <div class="am-session-duration_title">
                            <em class="am-light-blue">
                                <i class="am-icon-calender-minus"></i>
                            </em>
                            <span>{{ __('general.date') }}</span>
                        </div>
                        <strong @class(['am-rescheduled' =>  auth()->user()->role == 'student' && $currentBooking?->status == 'rescheduled'])>{{ parseToUserTz($currentBooking?->start_time)->format(setting('_general.date_format') ?? 'F j, Y') }}</strong>
                    </li>
                    <li>
                        <div class="am-session-duration_title">
                            <em class="am-light-purple">
                                <i class="am-icon-time"></i>
                            </em>
                            <span>{{ __('calendar.time') }}</span>
                        </div>
                        <strong @class(['am-rescheduled' => auth()->user()->role == 'student' && $currentBooking?->status == 'rescheduled'])>
                            @if(setting('_lernen.time_format') == '12')
                                {{ parseToUserTz($currentBooking?->start_time)->format('h:i a') }} -
                                {{ parseToUserTz($currentBooking?->end_time)->format('h:i a') }}
                            @else
                                {{ parseToUserTz($currentBooking?->start_time)->format('H:i') }} -
                                {{ parseToUserTz($currentBooking?->end_time)->format('H:i') }}
                            @endif
                        </strong>
                    </li>
                    <li>
                        <div class="am-session-duration_title">
                            <em class="am-light-red">
                                <i class="am-icon-layer-01"></i>
                            </em>
                            <span>{{ __('calendar.type') }}</span>
                        </div>
                        <strong>
                            {{ $currentBooking?->spaces > 1 ? __('calendar.group') : __('calendar.one') }}
                        </strong>
                    </li>
                    <li>
                        <div class="am-session-duration_title">
                            <em class="am-light-orange">
                                <i class="am-icon-user-group"></i>
                            </em>
                            <span>{{ __('calendar.total_enrollment') }}</span>
                        </div>
                        <strong>{{ __('calendar.booked_students', ['count' => $currentBooking?->bookings_count]) }}</strong>
                    </li>
                    <li>
                        <div class="am-session-duration_title">
                            <em class="am-light-green">
                                <i class="am-icon-dollar"></i>
                            </em>
                            <span>{{ __('calendar.session_fee') }}</span>
                        </div>
                        <strong> {{ formatAmount($currentBooking?->session_fee) }}<em>{{ __('calendar.person') }}</em></strong>
                    </li>
                    <li>
                        <div class="am-session-duration_title">
                            <figure>
                                @if(!empty($currentBooking?->tutor?->image) && Storage::disk(getStorageDisk())->exists($currentBooking?->tutor?->image))
                                    <img src="{{ resizedImage($currentBooking?->tutor?->image, 24, 24) }}" alt="{{ $currentBooking?->tutor?->full_name }}">
                                @else
                                    <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : resizedImage('placeholder.png', 24, 24) }}" alt="{{ $currentBooking?->tutor?->full_name }}">
                                @endif
                            </figure>
                            <span><em>{{ __('calendar.session_tutor') }}</em></span>
                        </div>
                        <strong>
                            @role('tutor') <em>{{ __('calendar.you') }}</em> @endrole
                            {{ $currentBooking?->tutor?->full_name }}
                        </strong>
                    </li>
                </ul>
            </div>
            <div class="am-session-detail-modal_body">
                <figure></figure>
                <div class="am-session-content">
                    {!! $currentBooking?->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
