<div class="col-12 col-lg-8 col-xl-9" id="am-tutor_list" wire:init="loadPage" x-data="{
        message: @entangle('message'),
        recepientId: @entangle('recepientId'),
        charLeft:500,
        init(){
            this.updateCharLeft();
        },
        tutorInfo:{},
        updateCharLeft() {
            let maxLength = 500;
            let messageLength = this.message ? this.message.length : 0;
            if (messageLength ?? 0 > maxLength) {
                this.message = this.message.substring(0, maxLength);
            }
            this.charLeft = maxLength - messageLength ?? 0;
        }
    }">
    @if(empty($isLoadPage))
    <div>
        @include('skeletons.tutor-list')
    </div>
    @else
    {{-- Limita la altura del dropdown de select2 a 5 opciones --}}

    <div class="am-searchfilter">
        <div class="am-searchfilter_item" style="max-height:80px;">
            <span class="am-searchfilter_title" style="max-height: 50px">{{ __('subject.subject_group') }}</span>
            <span class="am-select" style="max-height:50px;width:100%;display:block;">
                <select id="group_id" class="am-select2" data-searchable="true" data-class="am-filter-dropdown"
                    data-placeholder="{{ __('subject.choose_subject_group') }}" wire:model="group_id">
                    <option value=""> </option>
                    @foreach ($subjectGroups as $group)
                    <option value="{{ $group->id }}" {{ $group->id == ($filters['group_id'] ?? '') ? 'selected' : ''
                        }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </span>
        </div>




        <div class="am-searchfilter_item" style="margin-bottom: 1rem; max-height: 80px;">
           {{--  @php
                     dd($subjects,"dasddasdsads");
                    @endphp --}}
            <span class="am-searchfilter_title" style="max-height: 50px">{{ __('subject.choose_subject_label') }}</span>
            <span class="am-select" style="max-height:50px;width:100%;display:block;">
                <select id="subject_id" class="am-select2" data-searchable="true" data-class="am-filter-dropdown"
                    data-placeholder="{{ __('subject.choose_subject') }}" multiple wire:model.live="subject_id"
                    style="display:none;">
                    <option value=""></option>
                    
                    @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $subject_id ?? []) ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </span>
        </div>
    </div>

    <div class="d-none tutors-skeleton" wire:target="filters" wire:loading.class.remove="d-none">
        @include('skeletons.tutor-list')
    </div>
    <div wire:loading.class="d-none" wire:target="filters" class="am-tutorlist">
        @if($tutors->isNotEmpty())
        <div class="am-tutorsearch">
            @foreach ($tutors as $tutor)
            @php
            $tutorInfo['name'] = $tutor->profile->full_name;
            $tutorInfo['id'] = $tutor?->id;
            if (!empty($tutor->profile->image) && Storage::disk(getStorageDisk())->exists($tutor->profile->image)) {
            $tutorInfo['image'] = url('storage/profile_images/' . basename($tutor->profile->image));
            } else {
            $tutorInfo['image'] = setting('_general.default_avatar_for_user') ?
            url(Storage::url(setting('_general.default_avatar_for_user')[0]['path'])) :
            url('storage/profile_images/placeholder.png');
            }
            @endphp
            @if(!empty($tutor?->profile?->image))
            <div class="am-tutorsearch_card" id="profile-{{ $tutor->id }}">
                <div class="am-tutorsearch_video">
                    @php
                    $imagePath = public_path('storage/profile_images/' . basename($tutor?->profile?->image));
                    $imagePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $imagePath);
                    $defaultAvatar = setting('_general.default_avatar_for_user');

                    if (!empty($tutor?->profile?->image) && file_exists($imagePath)) {
                    $tutorInfo['image'] = url('storage/profile_images/' . basename($tutor->profile->image));
                    } elseif ($defaultAvatar && is_array($defaultAvatar) && isset($defaultAvatar[0]['path'])) {
                    $tutorInfo['image'] = url(Storage::url($defaultAvatar[0]['path']));
                    } else {
                    $tutorInfo['image'] = url('storage/profile_images/placeholder.png');
                    }
                    @endphp

                    <div class="d-flex justify-content-center mb-4" style="position: relative; display: inline-block;">
                        <img class="rounded-xl d-block mx-auto" src="{{ $tutorInfo['image'] }}"
                            alt="{{ $tutor->profile->full_name }}"
                            style="border-radius: 16px; width: 150px; height: 150px; object-fit: cover;">

                        @php
                        $now = \Carbon\Carbon::now();
                        $isAvailableNow = false;
                        if($tutor->userSubjectSlots && $tutor->userSubjectSlots->count() > 0) {
                        foreach($tutor->userSubjectSlots as $slot) {
                        $slotDate = \Carbon\Carbon::parse($slot->date);
                        if ($slotDate->isSameDay($now)) {
                        // Detecta si start_time y end_time ya tienen fecha
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
                        <div
                            style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 20px; height: 20px; background-color: #28a745; border-radius: 50%; border: 2px solid white;">
                        </div>
                        @else
                        <div
                            style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 20px; height: 20px; background-color:gray; border-radius: 50%; border: 2px solid white;">
                        </div>
                        @endif
                    </div>





                    <div class="am-tutorsearch_btns">



                        <a href="{{ route('tutor-detail',['slug' => $tutor->profile->slug]).'#availability' }}"
                            class="am-white-btn">{{ __('tutor.book_session') }}<i
                                class="am-icon-calender-duration"></i></a>
                        @if(Auth::check() && $allowFavAction)
                        <a href="javascript:;"
                            @click=" tutorInfo = @js($tutorInfo);threadId=''; recepientId=@js($tutor->id); $nextTick(() => $wire.dispatch('toggleModel', {id: 'message-model-'+@js($tutor->id),action:'show'}) )"
                            class="am-btn">{{ __('tutor.send_message') }}<i class="am-icon-chat-03"></i></a>
                        <a href="javascript:void(0);" id="toggleFavourite-{{ $tutor->id }}"
                            wire:click.prevent="toggleFavourite({{ $tutor->id }})" @class(['am-likebtn', 'active'=>
                            in_array($tutor->id, $favouriteTutors)])> <i class="am-icon-heart-01"></i></a>
                        @else
                        <a href="javascript:void(0);"
                            @click="$wire.dispatch('showAlertMessage', {type: `error`, message: `{{ Auth::check() ?  __('general.not_allowed') : __('general.login_error') }}` })"
                            class="am-btn">{{ __('tutor.send_message') }}<i class="am-icon-chat-03"></i></a>
                        <a href="javascript:void(0);"
                            @click="$wire.dispatch('showAlertMessage', {type: `error`, message: `{{ Auth::check() ?  __('general.not_allowed') : __('general.login_error') }}` })"
                            class="am-likebtn"><i class="am-icon-heart-01"></i></a>
                        @endif
                    </div>
                </div>
                <div class="am-tutorsearch_content">
                    <div class="am-tutorsearch_head">
                        <div class="am-tutorsearch_user">


                            @php
                            $imagePath = public_path('storage/profile_images/' . basename($tutor?->profile?->image));
                            $imagePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $imagePath);
                            $defaultAvatar = setting('_general.default_avatar_for_user');
                            if (!empty($tutor?->profile?->image) && file_exists($imagePath)) {
                            $userImage = url('storage/profile_images/' . basename($tutor->profile->image));
                            } elseif ($defaultAvatar && is_array($defaultAvatar) && isset($defaultAvatar[0]['path'])) {
                            $userImage = url(Storage::url($defaultAvatar[0]['path']));
                            } else {
                            $userImage = url('storage/profile_images/placeholder.png');
                            }
                            @endphp
                            <figure class="am-tutorvone_img">
                                <img src="{{ $userImage }}" class="am-user_image"
                                    alt="{{ $tutor->profile->full_name }}" />
                                {{-- <span @class(['am-userstaus', 'am-userstaus_online'=> $tutor->is_online])></span>
                                --}}
                            </figure>
                            <div class="am-tutorsearch_user_name">
                                <h3>
                                    <a href="{{ route('tutor-detail',['slug' => $tutor->profile->slug]) }}">{{
                                        $tutor->profile->full_name }}</a>


                                    @if($tutor?->profile?->verified_at)
                                    <x-frontend.verified-tooltip />
                                    @endif
                                    {{--
                                    @if(!empty($tutor?->address?->country?->short_code))
                                    <span
                                        class="flag flag-{{ strtolower($tutor?->address?->country?->short_code) }}"></span>
                                    @endif
                                    --}}


                                </h3>
                                @if($tutor->profile->tagline)
                                <span>
                                    Especialidad: {{ $tutor->profile->tagline }}
                                </span>
                                @endif
                            </div>
                        </div>

                        {{--
                        <div class="am-tutorsearch_fee">
                            <span>{{ __('tutor.session_fee') }}</span>
                            <strong>{{ formatAmount($tutor->min_price) }}<em>/{{ __('tutor.session') }}</em></strong>
                        </div>

                        --}}
                    </div>
                    <ul class="am-tutorsearch_info">
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-star-01"></i></div>
                            <span>{{ number_format($tutor->avg_rating, 1) }}<em>/5.0 ({{ $tutor->total_reviews == 1 ?
                                    __('general.review_count') : __('general.reviews_count', ['count' =>
                                    $tutor->total_reviews] ) }})</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-user-group"></i></div>
                            <span>{{$tutor->active_students}} <em>{{ $tutor->active_students == '1' ?
                                    __('tutor.active_student') : __('tutor.active_students') }}</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-menu-2"></i></div>
                            <span>{{ $tutor->subjects->count() }} <em>{{ $tutor->subjects->count() == 1 ?
                                    __('tutor.session') : __('tutor.sessions') }}</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-language-1"></i></div>
                            <span> {{ __('tutor.language_know') }}</span>
                            <div class="wa-tags-list">
                                <ul>
                                    @foreach ($tutor?->languages->slice(0, 3) as $index => $lan)
                                    <li><span>{{ ucfirst( $lan->name )}}</span></li>
                                    @endforeach
                                </ul>
                                @if($tutor?->languages?->count() > 3)
                                <div class="am-more am-custom-tooltip">
                                    <span class="am-tooltip-text">
                                        @php
                                        $tutorLanguages = $tutor?->languages->slice(3,
                                        $tutor?->languages?->count() - 1);
                                        @endphp
                                        @if (!empty($tutorLanguages))
                                        @foreach ($tutorLanguages as $lan)
                                        <span>{{ ucfirst( $lan->name )}}</span>
                                        @endforeach
                                        @endif
                                    </span>
                                    +{{ $tutor?->languages?->count() - 3 }}
                                </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                    @if(!empty($tutor->profile->description))
                    <div class="am-toggle-text">
                        <div class="am-addmore">
                            @php
                            $fullDescription = strip_tags($tutor->profile->description);
                            $shortDescription = Str::limit($fullDescription, 220, preserveWords: true);
                            @endphp
                            @if (Str::length($fullDescription) > 220)
                            <div class="short-description">
                                {{ __('tutor.Description') }}: {!! $shortDescription !!}
                            </div>
                            @else
                            <div class="full-description">
                                {{ __('tutor.Description') }}: {!! $fullDescription !!}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="am-tutorsearch_card am-tutorsearch_novideo" id="profile-{{ $tutor->id }}">
                <div class="am-tutorsearch_content">
                    <div class="am-tutorsearch_head">
                        <div class="am-tutorsearch_user">
                            @php
                            $imagePath = public_path('storage/profile_images/' . basename($tutor?->profile?->image));
                            $imagePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $imagePath);
                            $defaultAvatar = setting('_general.default_avatar_for_user');
                            if (!empty($tutor?->profile?->image) && file_exists($imagePath)) {
                            $userImage = url('storage/profile_images/' . basename($tutor->profile->image));
                            } elseif ($defaultAvatar && is_array($defaultAvatar) && isset($defaultAvatar[0]['path'])) {
                            $userImage = url(Storage::url($defaultAvatar[0]['path']));
                            } else {
                            $userImage = url('storage/profile_images/placeholder.png');
                            }
                            @endphp
                            <figure class="am-tutorvone_img">
                                <img src="{{ $userImage }}" class="am-user_image"
                                    alt="{{ $tutor->profile->full_name }}" />
                                <span @class(['am-userstaus', 'am-userstaus_online'=> $tutor->is_online])></span>
                            </figure>
                            <div class="am-tutorsearch_user_name">
                                <h3>
                                    <a href="{{ route('tutor-detail',['slug' => $tutor->profile->slug]) }}">{{
                                        $tutor->profile->full_name }}</a>
                                    @if($tutor?->profile?->verified_at)
                                    <x-frontend.verified-tooltip />
                                    @endif
                                    @if(!empty($tutor?->address?->country?->short_code))
                                    <span
                                        class="flag flag-{{ strtolower($tutor?->address?->country?->short_code) }}"></span>
                                    @endif
                                </h3>
                                @if($tutor->profile->tagline)
                                <span>
                                    {{ $tutor->profile->tagline }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="am-tutorsearch_fee">
                            <span>{{ __('tutor.session_fee') }}</span>
                            <strong>{{ formatAmount($tutor->min_price) }}<em>/{{ __('tutor.session') }}</em></strong>
                        </div>
                    </div>
                    <ul class="am-tutorsearch_info">
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-star-01"></i></div>
                            <span>{{ number_format($tutor->avg_rating, 1) }}<em>/5.0 ({{ $tutor->total_reviews == 1 ?
                                    __('general.review_count') : __('general.reviews_count', ['count' =>
                                    $tutor->total_reviews] ) }})</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-user-group"></i></div>
                            <span>{{$tutor->active_students}} <em>{{ $tutor->active_students == '1' ?
                                    __('tutor.active_student') : __('tutor.active_students') }}</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-menu-2"></i></div>
                            <span>{{ $tutor->subjects->count() }} <em>{{ $tutor->subjects->count() == 1 ?
                                    __('tutor.session') : __('tutor.sessions') }}</em></span>
                        </li>
                        <li>
                            <div class="am-tutorsearch_info_icon"><i class="am-icon-language-1"></i></div>
                            <span> {{ __('tutor.language_know') }}</span>
                            <div class="wa-tags-list">
                                <ul>
                                    @foreach ($tutor?->languages->slice(0, 3) as $index => $lan)
                                    <li><span>{{ ucfirst( $lan->name )}}</span></li>
                                    @endforeach
                                </ul>
                                @if($tutor?->languages?->count() > 3)
                                <div class="am-more am-custom-tooltip">
                                    <span class="am-tooltip-text">
                                        @php
                                        $tutorLanguages = $tutor?->languages->slice(3,
                                        $tutor?->languages?->count() - 1);
                                        @endphp
                                        @if (!empty($tutorLanguages))
                                        @foreach ($tutorLanguages as $lan)
                                        <span>{{ ucfirst( $lan->name )}}</span>
                                        @endforeach
                                        @endif
                                    </span>
                                    +{{ $tutor?->languages?->count() - 3 }}
                                </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                    @if(!empty($tutor->profile->description))
                    <div class="am-toggle-text">
                        <div class="am-addmore">
                            @php
                            $fullDescription = strip_tags($tutor->profile->description);
                            $shortDescription = Str::limit($fullDescription, 220, preserveWords: true);
                            @endphp
                            @if (Str::length($fullDescription) > 220)
                            <div class="short-description">
                                {!! $shortDescription !!}
                            </div>
                            @else
                            <div class="full-description">
                                {!! $fullDescription !!}
                            </div>
                            @endif
                        </div>
                        <div class="am-tutorsearch_btns">
                            <a href="{{ route('tutor-detail',['slug' => $tutor->profile->slug]).'#availability' }}"
                                class="btn-book-session">
                                {{ __('tutor.book_session') }} <i class="am-icon-calender-duration"></i>
                            </a>
                            @if(Auth::check() && $allowFavAction)
                            <a href="javascript:;"
                                @click=" tutorInfo = @js($tutorInfo);threadId=''; recepientId=@js($tutor->id); $nextTick(() => $wire.dispatch('toggleModel', {id: 'message-model-'+@js($tutor->id),action:'show'}) )"
                                class="am-btn">{{ __('tutor.send_message') }}<i class="am-icon-chat-03"></i></a>
                            <a href="javascript:void(0);" id="toggleFavourite-{{ $tutor->id }}"
                                wire:click.prevent="toggleFavourite({{ $tutor->id }})" @class(['am-likebtn', 'active'=>
                                in_array($tutor->id, $favouriteTutors)])> <i class="am-icon-heart-01"></i></a>
                            @else
                            <a href="javascript:void(0);"
                                @click="$wire.dispatch('showAlertMessage', {type: `error`, message: `{{ Auth::check() ?  __('general.not_allowed') : __('general.login_error') }}` })"
                                class="btn-send-message">{{ __('tutor.send_message') }}<i
                                    class="am-icon-chat-03"></i></a>
                            <a href="javascript:void(0);"
                                @click="$wire.dispatch('showAlertMessage', {type: `error`, message: `{{ Auth::check() ?  __('general.not_allowed') : __('general.login_error') }}` })"
                                class="am-likebtn"><i class="am-icon-heart-01"></i></a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @endforeach
            <div class="am-pagination am-pagination_two">
                {{ $tutors->links('pagination.pagination', data:['scrollTo'=>false]) }}
            </div>
        </div>
        @else
        <div class="am-norecord-found">
            <figure>
                <img src="{{ asset('images/subjects.png') }}" alt="no record">
            </figure>
            <strong>{{ __('general.not_found') }}
                <span>{{ __('general.not_found_desc') }}</span>
            </strong>
        </div>
        @endif
    </div>
    @endif
    @include('livewire.pages.tutor.action.message')
</div>


@push('styles')

@endpush


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('toggleFavIcon', (event) => {
                $(`#toggleFavourite-${event.detail.userId}`).toggleClass('active');
            })
            document.addEventListener('initVideoJs', (event) => {
                setTimeout(() => {
                    jQuery('.video-js').each((index, item) => {
                        item.onloadeddata =  function(){
                            videojs(item);
                        }
                    })
                }, event.detail.timeout ?? 500);
            });
        });
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para inicializar Select2
    function initializeSelect2() {
        $('#subject_id').select2({
            placeholder: '{{ __("subject.choose_subject") }}',
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'am-filter-dropdown',
            maximumSelectionLength: 10, // Limitar selecciones si es necesario
        });

        // Manejar cambios en Select2 y sincronizar con Livewire
        $('#subject_id').on('change', function() {
            let selectedValues = $(this).val() || [];
            @this.set('subject_id', selectedValues);
        });
    }

    // Inicializar cuando el DOM esté listo
    initializeSelect2();

    // Re-inicializar cuando Livewire actualice el componente
    document.addEventListener('livewire:updated', function() {
        setTimeout(function() {
            // Destruir Select2 existente si existe
            if ($('#subject_id').hasClass('select2-hidden-accessible')) {
                $('#subject_id').select2('destroy');
            }
            initializeSelect2();
        }, 100);
    });

    // Re-inicializar cuando cambie el grupo (subjects se actualizan)
    Livewire.on('subjectsUpdated', function() {
        setTimeout(function() {
            if ($('#subject_id').hasClass('select2-hidden-accessible')) {
                $('#subject_id').select2('destroy');
            }
            $('#subject_id').select2({
                placeholder: '{{ __("subject.choose_subject") }}',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'am-filter-dropdown',
                maximumSelectionLength: 10,
            });
        }, 250); // Aumenta el timeout para asegurar que el DOM esté listo
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function initializeSelect2() {
            $('#subject_id').select2({
                placeholder: '{{ __("subject.choose_subject") }}',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'am-filter-dropdown',
                maximumSelectionLength: 10,
                dropdownParent: $('#subject_id').parent(),
            });
            // Mostrar el select solo cuando select2 esté listo
            $('#subject_id').next('.select2').on('mousedown', function() {
                $('#subject_id').show();
            });
            // Ocultar el select nativo cuando se cierra el dropdown
            $('#subject_id').on('select2:close', function() {
                $('#subject_id').hide();
            });
            // Ocultar el select nativo por defecto
            $('#subject_id').hide();
        }
        initializeSelect2();
        document.addEventListener('livewire:updated', function() {
            setTimeout(function() {
                if ($('#subject_id').hasClass('select2-hidden-accessible')) {
                    $('#subject_id').select2('destroy');
                }
                initializeSelect2();
            }, 100);
        });
        Livewire.on('subjectsUpdated', function() {
            setTimeout(function() {
                if ($('#subject_id').hasClass('select2-hidden-accessible')) {
                    $('#subject_id').select2('destroy');
                }
                initializeSelect2();
            }, 250);
        });
    });
</script>
@endpush