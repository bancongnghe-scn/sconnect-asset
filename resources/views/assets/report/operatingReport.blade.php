@extends('assets.report.baseReport')

@section('content')
@include('assets.report.components.tabBar')
    <div x-data="operatingReport">
        <div class="row">
            <div class="col-xxl-6 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;">
                  <h5 class="mb-3" style="font-weight: 600;">Biểu đồ chi phí vận hành tài sản</h5>
                  <span>Đơn vị: <span id="unitOperatingChart"></span> đồng</span>
                  <canvas x-data="chartOperatingCost()" x-init="fetchDataAndInitChart()" id="chartOperatingCost"></canvas>
                </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;">
                    <h5 class="mb-3" style="font-weight: 600;">Biểu đồ chi phí vận hành tài sản</h5>
                    <span>Đơn vị: <span id="unitOperatingStructureChart"></span> đồng</span>
                    <canvas id="stackedBarChart" x-data="stackedBarChart()" x-init="fetchDataAndInitChart()"></canvas>
                </div>
            </div>
        </div>
    </div>
    <style>
        table tr td{
            background-color: #fff !important;
            font-size: 14px !important;
        }
    </style>
@endsection

@section('js')
<script>
    function chartOperatingCost() {
        return {
            chart: null,
            chartData: {
            labels: [],
            datasets: [{
                label: 'Chi phí vận hành tài sản',
                data: [],
                backgroundColor: '#3792374f',
                borderColor: '#379237cf',
                borderWidth: 1,
              }
            ]
            },
            async fetchDataAndInitChart() {
                try {
                    const response = await axios.get('/api/report/get-data-operating-report');
                    const data = response.data;

                    this.chartData.labels = data.data.data.labels;
                    this.chartData.datasets[0].data = data.data.data.values;
                    
                    document.getElementById('unitOperatingChart').innerText = data.data.unit;

                    const ctx = document.getElementById('chartOperatingCost').getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: this.chartData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });

                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            }
        };
    }

    function stackedBarChart() {
      return {
        async fetchDataAndInitChart() {
                try {
                    const response = await axios.get('/api/report/get-data-operating-report');
                    const data = response.data;
                    
                    document.getElementById('unitOperatingStructureChart').innerText = data.data.unit;

                    const ctx2 = document.getElementById('stackedBarChart').getContext('2d');
                    this.chart = new Chart(ctx2, {
                      type: 'bar',
                      data: {
                        labels: data.data.data.labels,
                        datasets: [
                          {
                            label: 'Chi phí bảo dưỡng',
                            data: data.data.data.maintainCosts,
                            backgroundColor: '#bf8c0087',
                            borderColor: '#BF8C00',
                            borderWidth: 1
                          },
                          {
                            label: 'Chi phí sửa chữa',
                            data: data.data.data.repairCosts,
                            backgroundColor: '#ff95003b',
                            borderColor: '#FF9500',
                            borderWidth: 1
                          },
                        ],
                      },
                      options: {
                        responsive: true,
                        plugins: {
                          legend: {
                            position: 'top',
                          },
                        },
                        scales: {
                          x: {
                            stacked: true,
                          },
                          y: {
                            stacked: true,
                          },
                        },
                      },
                    });

                } catch (error) {
                    console.error('Lỗi khi gọi API:', error);
                }
            }
      };
    }
</script>
<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('operatingReport', () => ({

    }));
});
</script>
@endsection