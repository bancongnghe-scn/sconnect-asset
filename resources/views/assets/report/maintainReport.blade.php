@extends('assets.report.baseReport')

@section('content')
@include('assets.report.components.tabBar')
    <div x-data="valueReport">
        <div class="row">
            <div class="col-xxl-6 col-sm-12">
                <div x-data="chartComponent()" x-init="fetchDataAndInitChart" style="background: #fff; padding: 30px; border-radius: 15px;">
                    <h5 class="mb-3" style="font-weight: 600;">Biểu đồ tổng số tài sản bảo dưỡng theo kế hoạch</h5>
                    <span>Đơn vị: Tài sản</span>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
                <div x-data="chartMoneyComponent()" x-init="fetchMoneyDataAndInitChart" style="background: #fff; padding: 30px; border-radius: 15px;">
                    <h5 class="mb-3" style="font-weight: 600;">Biểu đồ tổng số tiền bảo dưỡng theo kế hoạch</h5>
                    <span>Đơn vị: VNĐ</span>
                    <canvas id="barMoneyChart"></canvas>
                </div>
            </div>

            <div class="col-xxl-12 col-sm-12" style="margin-top: 20px;">
                <div style="background: #fff; padding: 30px; border-radius: 15px;" x-data="fetchDataComponent()" x-init="fetchData()">
                    <h5 class="mb-3" style="font-weight: 600;">Bảng theo dõi số lượng tài sản đến kì bảo dưỡng</h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Đến kỳ bảo dưỡng</th>
                            <th class="text-center">Số lượng tài sản</th>
                            <th class="text-left">Chi tiết</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Ngày mai</td>
                                <td class="text-center">3</td>
                                <td class="text-left">1 màn hình, 2 case máy tính</td>
                            </tr>
                            <tr>
                                <td class="text-center">Trong 1 tuần tới</td>
                                <td class="text-center">22</td>
                                <td class="text-left">10 màn hình, 12 case máy tính</td>
                            </tr>
                            <tr>
                                <td class="text-center">Trong 1 tháng tới</td>
                                <td class="text-center">34</td>
                                <td class="text-left">10 màn hình, 24 case máy tính</td>
                            </tr>
                        {{-- <template x-for="(value, index) in values">
                            <tr>
                                <td class="text-center" x-text="labels[index]"></td>
                                <td class="text-center text-bold" x-text="value"></td>
                                
                            </tr>
                        </template> --}}
                        </tbody>
                  </table>
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
    function chartComponent() {
      return {
        chart: null,
        chartData: {
          labels: [],
          datasets: [{
                label: 'Tổng số tài sản',
                data: [],
                backgroundColor: '#3792372b',
                borderColor: '#379237',
                borderWidth: 1
            },
            {
            label: "Tổng số tài sản (line)",
            data: [],
            backgroundColor: "#fff",
            borderColor: "#125412",
            borderWidth: 2,
            fill: false,
            type: "line",
            tension: 0.4,
            },
            ]
        },
        async fetchDataAndInitChart() {
            try {
                const response = await axios.get('/api/report/get-data-maintain-report');
                const data = response.data;

                this.chartData.labels = data.data.arrLabels;
                this.chartData.datasets[0].data = data.data.arrQuantity;
                this.chartData.datasets[1].data = data.data.arrQuantity;

                const ctx = document.getElementById('barChart').getContext('2d');
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

    function chartMoneyComponent() {
      return {
        chart: null,
        chartData: {
          labels: [],
          datasets: [{
                label: 'Tổng giá trị tài sản',
                data: [],
                backgroundColor: '#007bff2b',
                borderColor: '#007bff',
                borderWidth: 1
            },
            {
            label: "Tổng giá trị tài sản (line)",
            data: [],
            backgroundColor: "#fff",
            borderColor: "#085ab2",
            borderWidth: 2,
            fill: false,
            type: "line",
            tension: 0.4,
            },
            ]
        },
        async fetchMoneyDataAndInitChart() {
            try {
                const response = await axios.get('/api/report/get-data-maintain-report');
                const data = response.data;

                this.chartData.labels = data.data.arrLabels;
                this.chartData.datasets[0].data = data.data.arrTotalMoney;
                this.chartData.datasets[1].data = data.data.arrTotalMoney;

                const ctx = document.getElementById('barMoneyChart').getContext('2d');
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

// function fetchDataComponent() {
//     return {
//         // title: '',
//         labels: '',
//         arrDifference: [],
//         values: [],

//         async fetchData() {
//             try {
//                 const response = await axios.get('/api/report/get-data-value-report');
//                 const data = response.data;

//                 // this.title = response.data.title;
//                 // this.description = response.data.description;
//                 this.values = data.data.data.values;
//                 this.labels = data.data.data.labels;
//                 this.arrDifference = data.data.data.arrDifference;
//                 console.log( data.data.data);
//             } catch (error) {
//                 console.error('Lỗi khi gọi API:', error);
//             }
//         }
//     };
// }
</script>
<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('valueReport', () => ({

    }));
});
</script>
@endsection