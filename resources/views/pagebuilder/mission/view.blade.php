<section class="am-mission_section {{ pagesetting('style_variation') }}">
    <div class="container">
        @if(!empty(pagesetting('pre_heading')) 
            || !empty(pagesetting('heading')) 
            || !empty(pagesetting('paragraph')) 
            || !empty(pagesetting('list_data')))
            <div class="row">
                @if(!empty(pagesetting('pre_heading')) 
                    || !empty(pagesetting('heading')) 
                    || !empty(pagesetting('paragraph')) 
                    || !empty(pagesetting('list_data')))
                    <div class="col-12 col-lg-6 order-2 order-lg-1">
                        <div class="am-content_box am-left-text {{ pagesetting('select_title_verient') }}">
                            @if(!empty(pagesetting('pre_heading'))) <span>{{ pagesetting('pre_heading') }}</span> @endif 
                            @if(!empty(pagesetting('heading'))) <h3>{!! pagesetting('heading') !!}</h3> @endif
                            @if(!empty(pagesetting('paragraph'))) <p>{{ pagesetting('paragraph') }}</p> @endif
                            @if(!empty(pagesetting('list_data')))
                                <ul>
                                    @foreach(pagesetting('list_data') as $data)
                                        @if(!empty($data['item_heading']) || !empty($data['list_item'])) 
                                          
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                          
                        </div>
                    </div>
                @endif
                @if(!empty(pagesetting('mission_frame_image')) 
                    || !empty(pagesetting('user_one_image')) 
                    || !empty(pagesetting('handshake_image')) 
                    || !empty(pagesetting('user_two_image'))
                    || !empty(pagesetting('image_heading'))
                    || !empty(pagesetting('courses_text')))
                    <div class="col-12 col-lg-6 order-1 order-lg-2">
                        <div class="am-mission_iframe">
                            @if(!empty(pagesetting('mission_frame_image')) 
                                || !empty(pagesetting('user_one_image')) 
                                || !empty(pagesetting('handshake_image')) 
                                || !empty(pagesetting('user_two_image'))
                                || !empty(pagesetting('image_heading'))
                                || !empty(pagesetting('courses_text')))
                                <figure>
                                    @if(!empty(pagesetting('mission_frame_image')))
                                        @if(!empty(pagesetting('mission_frame_image')[0]['path']))
                                            <img src="{{url(Storage::url(pagesetting('mission_frame_image')[0]['path']))}}" alt="Empowering learners image">
                                        @endif
                                    @endif
                                    @if(!empty(pagesetting('user_one_image')) 
                                        || !empty(pagesetting('handshake_image')) 
                                        || !empty(pagesetting('user_two_image'))
                                        || !empty(pagesetting('image_heading'))
                                        || !empty(pagesetting('courses_text')))
                                        <figcaption>
                                            @if(!empty(pagesetting('user_one_image')))
                                                @if(!empty(pagesetting('user_one_image')[0]['path']))
                                                    <img src="{{url(Storage::url(pagesetting('user_one_image')[0]['path']))}}" alt="User image">
                                                @endif
                                            @endif
                                         
                                           
                                            @if(!empty(pagesetting('courses_text')))
                                                <div class="am-mission_courses">
                                                    <span>{!! pagesetting('courses_text') !!}</span>
                                                </div>
                                            @endif
                                        </figcaption>
                                    @endif
                                </figure>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
