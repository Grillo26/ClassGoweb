<section class="am-vision-section">
    <div class="container-fluaid">
        @if(!empty(pagesetting('video'))
            || !empty(pagesetting('pre_heading')) 
            || !empty(pagesetting('heading')) 
            || !empty(pagesetting('paragraph')) 
            || !empty(pagesetting('list_data'))
            || !empty(pagesetting('discover_more_btn_text'))
            || !empty(pagesetting('discover_more_btn_url')))
            <div class="row">
                @if(!empty(pagesetting('video')))
                <div class="col-12 col-lg-6 container-video">
                    @if(!empty(pagesetting('video')))
                        <div class="col-12 col-lg-6 video-wrapper">
                            @if(!empty(pagesetting('video')[0]['path']))
                                <img src="{{ asset('images/Tugo_With_Phone.png') }}" 
                                     alt="Imagen de reemplazo" 
                                     class="custom-video-replacement"
                                     width="940" height="737">
                            @endif
                        </div>
                    @endif
                </div>
                
@endif
                @if(!empty(pagesetting('pre_heading')) 
                    || !empty(pagesetting('heading')) 
                    || !empty(pagesetting('paragraph')) 
                    || !empty(pagesetting('list_data'))
                    || !empty(pagesetting('discover_more_btn_text'))
                    || !empty(pagesetting('discover_more_btn_url')))
                    <div class="col-12 col-lg-6 tutor-vision">
                        <div class="am-tutor-vision">
                            <div class="am-content_box am-left-text">
                                @if(!empty(pagesetting('pre_heading'))) <span>{{ pagesetting('pre_heading') }}</span> @endif 
                                @if(!empty(pagesetting('heading'))) <h3>{!! pagesetting('heading') !!}</h3> @endif
                                @if(!empty(pagesetting('paragraph'))) <p>{!!pagesetting('paragraph') !!}</p> @endif
                                @if(!empty( pagesetting('list_data')))
                                    <ul>
                                        @foreach(pagesetting('list_data') as $data)
                                            @if(!empty($data['item_heading']) || !empty($data['list_item'])) 
                                              
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                                @if(!empty(pagesetting('discover_more_btn_text')))
                                    <a href="@if(!empty(pagesetting('discover_more_btn_url'))) {{ pagesetting('discover_more_btn_url') }} @endif" class="am-marketplace_content_list_btn">
                                        {{pagesetting('discover_more_btn_text') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
@pushOnce('styles')
    @vite(['public/css/videojs.css'])
@endpushOnce
@pushOnce('scripts')
    <script src="{{ asset('js/video.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                visionVideoJs();
            }, 500);
        }); 

        document.addEventListener('loadSectionJs', (event) => {
            if(event.detail.sectionId === 'vision'){
                visionVideoJs();
            }
        }); 

        function visionVideoJs(){
            if(typeof videojs !== 'undefined'){
                jQuery('.video-js').each((index, item) => {
                    item.onloadeddata =  function(){
                        videojs(item);
                    }
                })
            }
        }       
    </script>
@endpushOnce
