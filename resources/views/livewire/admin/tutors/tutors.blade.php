<main class="tb-main am-dispute-system am-user-system">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="tb-dhb-mainheading">
                <h4>{{ __('general.all_tutors') . ' (' . $tutors->total() . ')' }}</h4>

                <div class="tb-sortby">
                    <form class="tb-themeform tb-displistform">
                        <fieldset>
                            <div class="tb-themeform__wrap">
                                <div class="tb-actionselect">
                                    <a href="javascript:void(0)" id="add_user_click" class="tb-btn add-new"
                                       data-bs-toggle="modal" data-bs-target="#tb-add-user">
                                        {{ __('general.add_new_tutor') }} <i class="icon-plus"></i>
                                    </a>
                                </div>
                                <div class="tb-actionselect" wire:ignore>
                                    <div class="tb-select">
                                        <select data-componentid="@this" class="am-select2 form-control"
                                                data-searchable="false" data-live='true' id="verification"
                                                data-wiremodel="verification">
                                            <option value="">{{ __('All') }}</option>
                                            <option value="verified">{{ __('Verified') }}</option>
                                            <option value="unverified">{{ __('Unverified') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="tb-actionselect" wire:ignore>
                                    <div class="tb-select">
                                        <select data-componentid="@this" class="am-select2 form-control"
                                                data-searchable="false" data-live='true' id="filter_user"
                                                data-wiremodel="filterUser">
                                            <option value="">{{ __('All') }}</option>
                                            <option value="active">{{ __('Active') }}</option>
                                            <option value="inactive">{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="tb-actionselect" wire:ignore>
                                    <div class="tb-select">
                                        <select data-componentid="@this" class="am-select2 form-control"
                                                data-searchable="false" data-live='true' id="sort_by"
                                                data-wiremodel="sortby">
                                            <option value="asc">{{ __('general.asc') }}</option>
                                            <option value="desc">{{ __('general.desc') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group tb-inputicon tb-inputheight">
                                    <i class="icon-search"></i>
                                    <input type="text" class="form-control"
                                           wire:model.live.debounce.500ms="search"
                                           autocomplete="off"
                                           placeholder="{{ __('general.search_tutor') }}">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

            <div class="am-disputelist_wrap">
                <div class="am-disputelist am-custom-scrollbar-y">
                    @if(!$tutors->isEmpty())
                        <table class="tb-table @if(setting('_general.table_responsive') == 'yes') tb-table-responsive @endif">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Verified') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tutors as $tutor)
                                    <tr>
                                        <td>{{ $tutor->id }}</td>
                                        <td>
                                            <div class="tb-varification_userinfo">
                                                <strong class="tb-adminhead__img">
                                                    @if (!empty($tutor->profile->image) && file_exists(public_path('storage/' . $tutor->profile->image)))
                                                        <img src="{{ asset('storage/' . $tutor->profile->image) }}" alt="{{ $tutor->profile->full_name }}" />
                                                    @else
                                                        <img src="{{ asset('images/placeholder.png') }}" alt="avatar" />
                                                    @endif
                                                </strong>
                                                <span>{{ $tutor->profile->full_name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $tutor->email }}</td>
                                        <td>{{ $tutor->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $tutor->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                                {{ $tutor->email_verified_at ? 'Verificado' : 'No verificado' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $tutor->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($tutor->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-sm btn-info">
                                                Ver
                                            </a>
                                            {{-- Puedes agregar más acciones aquí --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $tutors->links('pagination.custom') }}
                    @else
                        <x-no-record :image="asset('images/empty.png')" :title="__('general.no_record_title')"/>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
