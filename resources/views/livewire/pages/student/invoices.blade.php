<div class="am-dbbox am-invoicelist_wrap" wire:init="loadData">
    @if($isLoading)
    @include('skeletons.invoices')
    @else
    <div class="am-dbbox_content am-invoicelist">
        <div class="am-dbbox_title">
            @slot('title')
            {{ __('invoices.tutorials') }}
            @endslot
            <h2>{{ __('invoices.tutorials') }}</h2>
            {{-- <div class="am-dbbox_title_sorting">
                <em>{{ __('invoices.filter_by') }}</em>
                <span class="am-select" wire:ignore>
                    <select data-componentid="@this" data-live="true" class="am-select2" id="status"
                        data-wiremodel="status">
                        <option value="" {{ $status=='' ? 'selected' : '' }}>{{ __('invoices.all_invoices') }}</option>
                        <option value="pending" {{ $status=='pending' ? 'selected' : '' }}>{{ __('invoices.pending') }}
                        </option>
                        <option value="complete" {{ $status=='complete' ? 'selected' : '' }}>{{ __('invoices.complete')
                            }}</option>
                    </select>
                </span>
            </div> --}}
        </div>
        <div class="am-invoicetable">
            <table class="am-table @if(setting('_general.table_responsive') == 'yes') am-table-responsive @endif">
                <thead>
                    <tr>
                        <th>{{ __('booking.start_date') }}</th>
                        <th>{{ __('booking.end_date') }}</th>
                        <!--  <th>{{ __('booking.transaction_id') }}</th>-->

                        @role('tutor')
                        <th>{{ __('booking.student_name') }}</th>
                        <th>{{__('booking.status') }} </th>
                        @elserole('student')
                        <th>{{ __('booking.tutor_name') }}</th>
                        <th>Estado</th>
                        @endrole

                    </tr>
                </thead>
                <tbody>
                    @if (!$tutorias_completadas->isEmpty())
                    @foreach($tutorias_completadas as $order)



                    <tr>
                        <td data-label="{{ __('booking.id') }}"><span>{{ $order?->start_time }}</span></td>
                        <td data-label="{{ __('booking.id') }}"><span>{{ $order?->end_time }}</span></td>


                        @role('student')
                       


                        <td data-label="{{ __('booking.tutor_name' )}}">
                          
                            <span>
                                {{$order->tutor->first_name }} - {{$order->tutor->last_name}}
                            </span> 
                        </td>

                         
                        @elserole('tutor')
                        <td>
                            <span>
                                {{$order->booker->profile->first_name }} - {{$order->booker->profile->last_name}}
                            </span>
                        </td>

                        @endrole
                        <td data-label="{{ __('booking.status') }}">
                            @php
                            $status = $order['status'] ?? $order->status ?? '';
                            // Normaliza el status por si acaso
                            $status = ucfirst(strtolower($status));
                            $statusClass = match($status) {
                            'Aceptado' => 'bg-primary text-white',
                            'Pendiente' => 'bg-warning text-dark',
                            'No completado' => 'bg-danger text-white',
                            'Rechazado' => 'bg-secondary text-white',
                            'Completado' => 'bg-success text-white',
                            default => 'bg-secondary text-white',
                            };
                            @endphp
                            <span class="tk-project-tag-two {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>

                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @if ($tutorias_completadas->isEmpty())
        <x-no-record :image="asset('images/payouts.png')" :title="__('general.no_record_title')"
            :description="__('general.no_records_available')" />
        @else
        {{ $orders->links('pagination.pagination') }}
        @endif
    </div>
    @endif
</div>
@push('scripts' )
<script type="text/javascript" data-navigate-once>
    var component = '';
    document.addEventListener('livewire:navigated', function() {
            component = @this;
    },{ once: true });
    document.addEventListener('loadPageJs', (event) => {
        component.dispatch('initSelect2', {target:'.am-select2'});
    })
</script>
@endpush