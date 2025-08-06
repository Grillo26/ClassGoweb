<div class="am-insights-wrapper tb-custom-scrollbar">
    <div class="am-insights">


        {{-- pagos --}}
        <div class="am-insights_section am-revenue">
            @include('livewire.pages.admin.insights.components.pagos_ingresos')
        </div>


        {{-- reservas tutorías --}}
        <div class="am-insights_section am-engagement">
            @include('livewire.pages.admin.insights.components.reservas_tutorias')
        </div>


        {{-- usuarios roles y genero --}}
        <div class="am-insights_section am-activity">
            <div class="am-insights_header">
                <div class="am-insights_title">
                    <h2>{{ __('admin/general.user_metrics_activity') }}</h2>
                    <p>{{ __('admin/general.track_manage_income') }}</p>
                </div>
            </div>
            <div class="am-insights_content">
                <div class="am-activity_card">
                    <div class="am-activity_user">
                        <div class="am-activity_user_info">
                            <span>{{ __('admin/general.total_users') }}</span>
                            <strong>{{ $this->usersCount }}</strong>
                        </div>
                        @if ($this->users->isNotEmpty())
                        <figure class="am-activity_user_img">
                            @foreach ($this->users as $user)
                            @if (!empty($user->profile?->image) &&
                            Storage::disk(getStorageDisk())->exists($user->profile?->image))
                            <img src="{{ url(Storage::url($user->profile?->image)) }}"
                                alt="{{ $user->profile?->image }}">
                            @else
                            <img src="{{ resizedImage('placeholder.png',34,34) }}" alt="{{ $user->profile?->image }}" />
                            @endif
                            @endforeach
                            @endif
                    </div>
                    @include('livewire.pages.admin.insights.components.usuarios_totales')

                </div>
                <div class="am-activity_card am-activity_comparison_card">
                    <div class="am-activity_card_heading">
                        <h3>{{ __('admin/general.monthly_user_comparison') }}</h3>
                    </div>
                    <div class="am-activity_user_comparison">
                        <span>
                            {{ __('admin/general.this_month') }}
                            <strong class="{{ $difference < 0? 'am-loss' : 'am-profit' }}">
                                {{ number_format($currentMonthUsers) }} {{ __('admin/general.users') }}
                                <em>{{ $difference > 0 ? '+':''}}{{ $difference }}%</em>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none">
                                    <path
                                        d="M6.86879 5.79869L4.06487 8.60261C3.20811 9.45937 2.77974 9.88774 2.75079 10.2555C2.72568 10.5746 2.85485 10.8865 3.09826 11.0944C3.37879 11.334 3.98461 11.334 5.19624 11.334H10.8041C12.0157 11.334 12.6215 11.334 12.9021 11.0944C13.1455 10.8865 13.2746 10.5746 13.2495 10.2555C13.2206 9.88774 12.7922 9.45937 11.9355 8.60261L9.13153 5.79869C8.73552 5.40267 8.53751 5.20466 8.30918 5.13047C8.10834 5.06522 7.89199 5.06522 7.69115 5.13047C7.46282 5.20466 7.26481 5.40267 6.86879 5.79869Z"
                                        fill="#17B26A" />
                                </svg>
                            </strong>
                        </span>
                        <span>
                            {{ __('admin/general.last_month') }}
                            <strong>
                                {{ number_format($lastMonthUsers) }} {{ __('admin/general.users') }}
                            </strong>
                        </span>
                    </div>
                    @include('livewire.pages.admin.insights.components.genero')
                </div>
            </div>
        </div>


        <!-- filepath: c:\Users\alejandro\Desktop\ClassGo\Nueva carpeta\ClassGoweb\resources\views\livewire\pages\admin\insights\components\edades.blade.php -->


        <div class="charts-row">
            <div class="am-activity_card">
                <div class="am-activity_card_heading">
                    <h3>Distribución por Edades</h3>
                    <p>Rangos de edad de usuarios verificados</p>
                </div>
                <div class="age-chart-container" style="padding: 10px;">
                    <canvas id="ageChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="am-activity_card">
                <div class="am-activity_card_heading">
                    <h3>Horarios de Mayor Demanda</h3>
                    <p>Frecuencia de reservas por hora del día</p>
                </div>
                <div class="horarios-chart-container" style="padding: 10px;">
                    <canvas id="horariosChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>



    </div>
</div>

@push('styles')
@vite([
'public/admin/css/daterangepicker.css'
])
@endpush

@push('scripts')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>

<script>
    document.addEventListener('livewire:initialized', function() {

        jQuery('#revenue-date-range').daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: @json(__('general.clear'))
            },
            autoUpdateInput: true,
            alwaysShowCalendars: false,
            ranges: {
                'This month': [moment().startOf('month'), moment().endOf('month')],
                'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This year': [moment().startOf('year'), moment().endOf('year')],
                'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }
    
        ,function(start, end, label) {
            $('#revenue-date-range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
         });

         jQuery('#session-date-range').daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: @json(__('general.clear'))
            },
            autoUpdateInput: true,
            alwaysShowCalendars: false,
            ranges: {
                'This month': [moment().startOf('month'), moment().endOf('month')],
                'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This year': [moment().startOf('year'), moment().endOf('year')],
                'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }
    
        ,function(start, end, label) {
            $('#session-date-range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
         });

         $('#revenue-date-range').on('apply.daterangepicker', function(ev, picker) {
            @this.set('revenueStartDate', picker.startDate.format('YYYY-MM-DD'));
            @this.set('revenueEndDate', picker.endDate.format('YYYY-MM-DD'));
        });

         $('#session-date-range').on('apply.daterangepicker', function(ev, picker) {
            @this.set('sessionStartDate', picker.startDate.format('YYYY-MM-DD'));
            @this.set('sessionEndDate', picker.endDate.format('YYYY-MM-DD'));
        });

});
</script>
@endpush




<!-- filepath: c:\Users\alejandro\Desktop\ClassGo\Nueva carpeta\ClassGoweb\resources\views\livewire\pages\admin\insights\insights.blade.php -->
<!-- filepath: c:\Users\alejandro\Desktop\ClassGo\Nueva carpeta\ClassGoweb\resources\views\livewire\pages\admin\insights\insights.blade.php -->
@push('scripts')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('livewire:initialized', function() {
        // DateRangePicker código existente...
        jQuery('#revenue-date-range').daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: @json(__('general.clear'))
            },
            autoUpdateInput: true,
            alwaysShowCalendars: false,
            ranges: {
                'This month': [moment().startOf('month'), moment().endOf('month')],
                'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This year': [moment().startOf('year'), moment().endOf('year')],
                'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }, function(start, end, label) {
            $('#revenue-date-range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        });

        jQuery('#session-date-range').daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: @json(__('general.clear'))
            },
            autoUpdateInput: true,
            alwaysShowCalendars: false,
            ranges: {
                'This month': [moment().startOf('month'), moment().endOf('month')],
                'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This year': [moment().startOf('year'), moment().endOf('year')],
                'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }, function(start, end, label) {
            $('#session-date-range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        });

        $('#revenue-date-range').on('apply.daterangepicker', function(ev, picker) {
            @this.set('revenueStartDate', picker.startDate.format('YYYY-MM-DD'));
            @this.set('revenueEndDate', picker.endDate.format('YYYY-MM-DD'));
        });

        $('#session-date-range').on('apply.daterangepicker', function(ev, picker) {
            @this.set('sessionStartDate', picker.startDate.format('YYYY-MM-DD'));
            @this.set('sessionEndDate', picker.endDate.format('YYYY-MM-DD'));
        });

        // Gráfico de género
        const genderData = @json($genderStats);
        if (document.getElementById('genderChart')) {
            const ctx = document.getElementById('genderChart').getContext('2d');
            
            const genderChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Masculino', 'Femenino', 'No Especificado'],
                    datasets: [{
                        data: [
                            genderData.masculino?.count || 0,
                            genderData.femenino?.count || 0,
                            genderData.no_especificado?.count || 0
                        ],
                        backgroundColor: [
                            '#3B82F6', '#EC4899', '#6B7280'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de edades
        const ageData = @json($ageStats);
        if (document.getElementById('ageChart')) {
            const ctxAge = document.getElementById('ageChart').getContext('2d');
            
            const ageChart = new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: Object.keys(ageData).map(range => range + ' años'),
                    datasets: [{
                        label: 'Cantidad de usuarios',
                        data: Object.values(ageData),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ],
                        borderColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ],
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y} usuarios`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // Gráfico de horarios pico
        const horariosData = @json($horariosStats);
        if (document.getElementById('horariosChart')) {
            const ctxHorarios = document.getElementById('horariosChart').getContext('2d');
            
            const horariosChart = new Chart(ctxHorarios, {
                type: 'line',
                data: {
                    labels: Object.keys(horariosData),
                    datasets: [{
                        label: 'Reservas por hora',
                        data: Object.values(horariosData),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y} reservas a las ${context.label}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush


@push('styles')
<style>
    .age-chart-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .age-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
    }

    .age-stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .age-range {
        font-weight: 500;
        color: #374151;
    }

    .age-count {
        font-weight: 600;
        color: #1f2937;
    }



      /* Contenedor en fila para los gráficos */
    .charts-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .charts-row .am-activity_card {
        flex: 1;
        min-width: 300px; /* Ancho mínimo para responsive */
    }

    /* Estilos existentes */
    .age-chart-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .age-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
    }

    .age-stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .age-range {
        font-weight: 500;
        color: #374151;
    }

    .age-count {
        font-weight: 600;
        color: #1f2937;
    }

    /* Responsive para pantallas pequeñas */
    @media (max-width: 768px) {
        .charts-row {
            flex-direction: column;
        }
    }
</style>
@endpush