<div class="am-insights_section am-gender">
    <div class="am-insights_header">
        <div class="am-insights_title">
            <h2>Distribución por Género</h2>
            <p>Estadísticas de género de usuarios</p>
        </div>
    </div>
    <div class="am-insights_content">
        <div class="gender-chart-container">
            <canvas id="genderChart" width="300" height="300"></canvas>
            <div class="gender-stats">
                @foreach($genderStats as $gender => $data)
                <div class="gender-stat-item">
                    <span class="gender-dot gender-{{ $gender }}"></span>
                    <span class="gender-label">{{ ucfirst(str_replace('_', ' ', $gender)) }}</span>
                    <span class="gender-count">{{ $data['count'] }} ({{ $data['percentage']
                        }}%)</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>