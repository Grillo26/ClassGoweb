@if(!empty($booking))
    @php
        $tooltipClass = Arr::random(['warning', 'pending', 'ready', 'success'])
    @endphp
    <div @class([
        'am-reminder-tooltip',
        "am-$tooltipClass-tooltip" => $booking->status == 'disputed' || $booking->status == 'rescheduled',
        'am-blur-tooltip' => auth()->user()->role == 'student' && ($booking->status == 'rescheduled' || $booking->status == 'disputed')
        ])>
        <div class="am-reminder-tooltip_title am-titleblur">
            <figure>
                <img src="{{ setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : resizedImage('placeholder.png', 40, 40) }}" alt="Subject">
            </figure>
            <h2>
                {{-- Materia eliminada temporalmente --}}
                @if(false)
                    <span></span>
                @endif
                @if(false)
                    <span></span>
                @endif
                @if($booking->rating_exists)
                    <span class="am-reviewreqslot">
                        <i class="am-icon-check-circle06"></i> 
                        {{ __('calendar.review_submitted') }}
                    </span>
                @elseif($booking->status == 'completed')
                    @php
                        $tutorInfo['name'] = $booking->tutor->full_name;
                        if (!empty($booking?->tutor?->image) &&
                            Storage::disk(getStorageDisk())->exists($booking?->tutor?->image)) {
                            $tutorInfo['image'] = resizedImage($booking?->tutor?->image, 36, 36);
                        } else {
                            $tutorInfo['image'] = setting('_general.default_avatar_for_user') ? url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) : resizedImage('placeholder.png', 36, 36);
                        }
                    @endphp
                    <a href="#"
                        @click=" tutorInfo = @js($tutorInfo); form.bookingId = @js($booking->id); $nextTick(() => $wire.dispatch('toggleModel', {id:'review-modal',action:'show'}) )">
                        {{ __('calendar.add_review') }} 
                    </a>
                @elseif(auth()->user()->role == 'student' && $booking->status == 'disputed')
                    <a href="{{ route('student.manage-dispute', ['id' => $booking?->dispute?->uuid]) }}">{{ __('calendar.dispute_session') }}</a>  
                @else
                    <a href="#" wire:click.prevent="showCompletePopup({{ json_encode($booking) }})">
                        {{ __('calendar.mark_as_completed') }}
                    </a>
                @endif
            </h2>
        </div>
        @if(auth()->user()->role == 'student' && $booking->status == 'rescheduled')
            <div class="am-blur-content">
                <a href="{{ route('student.reschedule-session', ['id' => $booking->id]) }}" wire:navigate.remove>{{ __('calendar.tutor_rescheduled') }}</a>
            </div>
        @elseif(auth()->user()->role == 'student' && $booking->status == 'disputed')
            <div class="am-blur-content">
                <a href="{{ route('student.manage-dispute', ['id' => $booking?->dispute?->uuid]) }}">{{ __('calendar.dispute_session') }}</a>   
            </div>
        @endif    
    </div> 
@else
    <div class="alert alert-danger">
        Error: La reserva no está disponible o es inválida.
    </div>
@endif
