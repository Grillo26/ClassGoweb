<section class="am-feedback am-feedback-two {{ pagesetting('enable_slider') == 'no' ? 'am-feedback-three' : '' }} {{ pagesetting('verient') }}"> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(!empty(pagesetting('pre_heading')) || !empty(pagesetting('heading')) || !empty(pagesetting('paragraph')) || !empty(pagesetting('feedback_repeater')))
                    <div class="am-feedback-two_wrap">
                        @if(!empty(pagesetting('pre_heading')) || !empty(pagesetting('heading')) || !empty(pagesetting('paragraph')))
                            <div class="am-section_title am-section_title_center {{ pagesetting('select_title_verient') }}">
                                @if(!empty(pagesetting('pre_heading'))) <span>{{ pagesetting('pre_heading') }}</span> @endif 
                                @if(!empty(pagesetting('heading'))) <h2>{!! pagesetting('heading') !!}</h2> @endif
                                @if(!empty(pagesetting('paragraph'))) <p>{{ pagesetting('paragraph') }}</p> @endif
                            </div>
                        @endif
                        @if(pagesetting('enable_slider') == 'yes')
                        <div class="am-testimonial-section" data-aos="fade-up"  data-aos-duration="1000" data-aos-easing="ease">
                            <div class="splide" id="testimonial-slider">
                                <div class="splide__track">
                        @endif
                        <div id="feedbackCarousel" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner am-feedback_content_list">
                                @foreach(pagesetting('feedback_repeater') as $key => $option)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <li class="{{ pagesetting('enable_slider') == 'yes' ? 'splide__slide' : '' }}">
                                            @if(pagesetting('enable_slider') == 'yes')<div class="am-testimonial-card">@endif
                                            <div class="am-feedback_content_list_info">
                                                <div class="am-feedback_content_list_info_image"> 
                                                    @if(!empty($option['tutor_image']))
                                                        <figure>
                                                            @if(!empty($option['tutor_image'][0]['path']))
                                                                <img src="{{url(Storage::url($option['tutor_image'][0]['path']))}}" alt="Profile image">
                                                            @endif
                                                        </figure>
                                                    @endif
                                                    @if(!empty($option['student_image']))
                                                        <span class="am-rating-company">
                                                            @if(!empty($option['student_image'][0]['path']))
                                                                <img src="{{url(Storage::url($option['student_image'][0]['path']))}}" alt="Profile image">
                                                            @endif      
                                                        </span>
                                                    @endif 
                                                </div>
                                                <div class="am-feedback_content_list_info_text">
                                                    @if(!empty($option['feedback_paragraph']))
                                                        <p>{!! $option['feedback_paragraph'] !!}</p>
                                                    @endif
                                                   <!-- @if(!empty($option['tutor_rating']))
                                                        <div class="am-feedback_content_list_stars">
                                                            @php $rating = $option['tutor_rating']; @endphp
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="am-icon-star-filled {{ $i <= $rating ? '' : 'am-icon-start-empty' }}"></i>
                                                            @endfor
                                                        </div>
                                                    @endif-->
                                                    <div class="am-feedback_content_list_info_text_prof"> 
                                                        <div>
                                                            @if(!empty($option['tutor_name'])) <h3>{!! $option['tutor_name'] !!}</h3> @endif
                                                            @if(!empty($option['tutor_tagline'])) <span>{!! $option['tutor_tagline'] !!}</span> @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(pagesetting('enable_slider') == 'yes')</div>@endif
                                        </li>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                        
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                        
                        @if(pagesetting('enable_slider') == 'yes')
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
    @vite(['public/css/flags.css'])
@endpush

@pushOnce('scripts')
    <script src="{{ asset('js/splide.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector('#testimonial-slider')) {
                initClientsFeedbackSlider();
            }
        });

        document.addEventListener('loadSectionJs', function (event) {
            if (event.detail.sectionId === 'clients-feedback') {
                if (document.querySelector('#testimonial-slider')) {
                    initClientsFeedbackSlider();
                }
            }
        });

        function initClientsFeedbackSlider() {
            new Splide('#testimonial-slider', {
                gap: '20px',
                perPage: 3,
                perMove: 1,
                focus: 1,
                type: 'loop',
                direction: document.documentElement.dir === 'rtl' ? 'rtl' : 'ltr', 
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
@endPushOnce
