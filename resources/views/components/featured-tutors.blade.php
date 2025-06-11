<div id="am-handpick-tutor" class="am-handpick-tutor splide">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach ($featuredTutors as $singleTutor)
                @if($singleTutor->profile->verified_at !== null)
                    <li class="splide__slide">
                        <div class="am-tutor-feature">
                            
                            <video autoplay muted loop class="video-js" src="{{ asset('storage/' . $singleTutor->profile->intro_video) }}" controls></video>
                            
                            <div class="am-tutorsearch_user">
                                <figure class="am-tutorvone_img">
                                    <img src="{{ asset('storage/' . $singleTutor->profile->image) }}" alt="Profile image">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $isAvailableNow = false;
                                        if($singleTutor->userSubjectSlots && $singleTutor->userSubjectSlots->count() > 0) {
                                            foreach($singleTutor->userSubjectSlots as $slot) {
                                                $slotDate = \Carbon\Carbon::parse($slot->date);
                                                if ($slotDate->isSameDay($now)) {
                                                    $start = (strlen($slot->start_time) > 8) ? \Carbon\Carbon::parse($slot->start_time) :
                                                    \Carbon\Carbon::parse($slot->date.' '.$slot->start_time);
                                                    $end = (strlen($slot->end_time) > 8) ? \Carbon\Carbon::parse($slot->end_time) :
                                                    \Carbon\Carbon::parse($slot->date.' '.$slot->end_time);
                                                    if ($now->between($start, $end)) {
                                                        $isAvailableNow = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    @if($isAvailableNow)
                                        <div style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); width: 12px; height: 12px; background-color: #28a745; border-radius: 50%; border: 2px solid white;"></div>
                                    @else
                                        <div style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); width: 12px; height: 12px; background-color: gray; border-radius: 50%; border: 2px solid white;"></div>
                                    @endif
                                </figure>
                                <div class="am-tutorsearch_user_name">
                                    <h3>
                                         
                                        <a href="{{ route('tutor-detail',['slug' => $singleTutor->profile->slug]) }}">
                                            {{ $singleTutor->profile->first_name }} {{ $singleTutor->profile->last_name }}
                                        </a>
                                        @if($singleTutor->profile->verified_at)
                                        <x-frontend.verified-tooltip />
                                        @endif
                                        @if ($singleTutor->address && $singleTutor->address->country && $singleTutor->address->country->short_code)
                                            {{-- <span class="flag flag-{{ strtolower($singleTutor->address->country->short_code) }}"></span> --}}
                                        @endif
                                    </h3>
                                    <span>
                                        @foreach ($singleTutor->educations as $singleEducation)
                                            {{ $singleEducation->course_title }}
                                        @endforeach
                                    </span>
                                </div>
                            </div>
                            <ul class="am-tutorsearch_info">
                               
                                <li>
                                    <div class="am-tutorsearch_info_icon"><i class="am-icon-star-01"></i></div>
                                    <span>{{ number_format($singleTutor->avg_rating, 1) }}<em>/5.0 ({{ $singleTutor->total_reviews == 1 ? __('general.review_count') : __('general.reviews_count', ['count' => $singleTutor->total_reviews] ) }})</em></span>

                                </li>
                                
                            </ul>
                            <a href="{{ route('tutor-detail', ['slug' => $singleTutor->profile->slug]) }}" class="am-white-btn">{{ __('general.profile') }}</a>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

@push('styles')
    @vite(['public/css/flags.css'])
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', (event)=>{
        initFeaturedTutorsSlider();
    });

    document.addEventListener('loadSectionJs', (event)=>{
        if(event.detail.sectionId === 'featured-tutors'){
            setTimeout(()=>{
                initFeaturedTutorsSlider();
            }, 500);
        }
    });

    function initFeaturedTutorsSlider(){
            new Splide('.am-handpick-tutor' , {
                autoWidth: true,
                    perMove: 1,
                pagination: false,
                breakpoints: {
                    1024: {
                        perPage: 2,
                    },
                    768: {
                        perPage: 1,
                    },
                },
            }).mount();
        }
</script>
@endpush
