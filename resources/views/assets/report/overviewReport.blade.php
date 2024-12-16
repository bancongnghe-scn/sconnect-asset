@extends('assets.report.baseReport')

@section('content')
@include('assets.report.components.tabBar')
    <div x-data="overviewReport">
        <div class="row">
            <div class="col-xxl-4 col-xl-4 justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M40 13.6875C40 14.7422 39.1063 15.7148 37.6 16.5C35.7812 17.4434 33.0687 18.1113 29.9562 18.3105C29.725 18.2051 29.4937 18.1055 29.25 18.0176C26.7875 17.0508 23.5125 16.5 20 16.5C19.4812 16.5 18.975 16.5117 18.4688 16.5352L18.4 16.5C16.8937 15.7148 16 14.7422 16 13.6875C16 11.0977 21.375 9 28 9C34.625 9 40 11.0977 40 13.6875ZM18.0437 18.4395C18.6812 18.3984 19.3375 18.375 20 18.375C23.8875 18.375 27.3375 19.0957 29.5312 20.2148C31.0812 21.0059 32 21.9902 32 23.0625C32 23.2969 31.9562 23.5254 31.8687 23.748C31.5812 24.5215 30.8062 25.2305 29.6812 25.8281C29.675 25.834 29.6625 25.834 29.6562 25.8398C29.6375 25.8516 29.6187 25.8574 29.6 25.8691C27.4125 27.0059 23.925 27.7441 20 27.7441C16.275 27.7441 12.9437 27.082 10.7375 26.0391C10.6187 25.9863 10.5062 25.9277 10.3937 25.8691C8.89375 25.0898 8 24.1172 8 23.0625C8 21.0234 11.3375 19.2832 16 18.6445C16.6562 18.5566 17.3375 18.4863 18.0437 18.4395ZM34 23.0625C34 21.7793 33.3375 20.7246 32.4937 19.9336C34.2625 19.6758 35.8813 19.2656 37.2563 18.7324C38.275 18.334 39.225 17.8418 40 17.2383V19.3125C40 20.4434 38.9688 21.4863 37.2625 22.2949C36.35 22.7285 35.2375 23.0977 33.9875 23.3789C33.9937 23.2734 34 23.1738 34 23.0684V23.0625ZM32 28.6875C32 29.7422 31.1063 30.7148 29.6 31.5C29.4875 31.5586 29.375 31.6113 29.2563 31.6699C27.0562 32.7129 23.725 33.375 20 33.375C16.075 33.375 12.5875 32.6367 10.4 31.5C8.89375 30.7148 8 29.7422 8 28.6875V26.6133C8.78125 27.2168 9.725 27.709 10.7438 28.1074C13.2125 29.0742 16.4875 29.625 20 29.625C23.5125 29.625 26.7875 29.0742 29.2563 28.1074C29.7437 27.9199 30.2125 27.7031 30.6562 27.4688C31.0375 27.2695 31.3938 27.0469 31.7313 26.8125C31.825 26.748 31.9125 26.6777 32 26.6133V26.8125V27.1465V28.6875ZM34 28.6875V26.8125V25.2949C35.1875 25.0488 36.2812 24.7383 37.2563 24.3574C38.275 23.959 39.225 23.4668 40 22.8633V24.9375C40 25.5527 39.6875 26.168 39.0688 26.748C38.05 27.7031 36.2562 28.4883 33.9875 28.998C33.9937 28.8984 34 28.793 34 28.6875ZM20 35.25C23.5125 35.25 26.7875 34.6992 29.2563 33.7324C30.275 33.334 31.225 32.8418 32 32.2383V34.3125C32 36.9023 26.625 39 20 39C13.375 39 8 36.9023 8 34.3125V32.2383C8.78125 32.8418 9.725 33.334 10.7438 33.7324C13.2125 34.6992 16.4875 35.25 20 35.25Z" fill="#682EED"/>
                            <path d="M0 10C0 4.47715 4.47715 0 10 0H38C43.5229 0 48 4.47715 48 10V38C48 43.5229 43.5228 48 38 48H10C4.47715 48 0 43.5228 0 38V10Z" fill="#692CE5" fill-opacity="0.08"/>
                            </svg>
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Tổng giá trị tài sản</span>
                        <span style="font-weight: 600; font-size: 21px; color: #682eec;">{{ formatNumberToReadable($total['total_money']) }}</span>
                        <span>{{ $total['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M27.2 15.4286V27.4286H11.2V15.4286H27.2ZM11.2 12C9.435 12 8 13.5375 8 15.4286V27.4286C8 29.3196 9.435 30.8571 11.2 30.8571H17.065L16.53 32.5714H12.8C11.915 32.5714 11.2 33.3375 11.2 34.2857C11.2 35.2339 11.915 36 12.8 36H25.6C26.485 36 27.2 35.2339 27.2 34.2857C27.2 33.3375 26.485 32.5714 25.6 32.5714H21.865L21.33 30.8571H27.2C28.965 30.8571 30.4 29.3196 30.4 27.4286V15.4286C30.4 13.5375 28.965 12 27.2 12H11.2ZM34.4 12C33.075 12 32 13.1518 32 14.5714V33.4286C32 34.8482 33.075 36 34.4 36H37.6C38.925 36 40 34.8482 40 33.4286V14.5714C40 13.1518 38.925 12 37.6 12H34.4ZM35.2 15.4286H36.8C37.24 15.4286 37.6 15.8143 37.6 16.2857C37.6 16.7571 37.24 17.1429 36.8 17.1429H35.2C34.76 17.1429 34.4 16.7571 34.4 16.2857C34.4 15.8143 34.76 15.4286 35.2 15.4286ZM34.4 19.7143C34.4 19.2429 34.76 18.8571 35.2 18.8571H36.8C37.24 18.8571 37.6 19.2429 37.6 19.7143C37.6 20.1857 37.24 20.5714 36.8 20.5714H35.2C34.76 20.5714 34.4 20.1857 34.4 19.7143ZM36 28.2857C36.4243 28.2857 36.8313 28.4663 37.1314 28.7878C37.4314 29.1093 37.6 29.5453 37.6 30C37.6 30.4547 37.4314 30.8907 37.1314 31.2122C36.8313 31.5337 36.4243 31.7143 36 31.7143C35.5757 31.7143 35.1687 31.5337 34.8686 31.2122C34.5686 30.8907 34.4 30.4547 34.4 30C34.4 29.5453 34.5686 29.1093 34.8686 28.7878C35.1687 28.4663 35.5757 28.2857 36 28.2857Z" fill="#00CEB6"/>
                            <path d="M0 10C0 4.47715 4.47715 0 10 0H38C43.5229 0 48 4.47715 48 10V38C48 43.5229 43.5228 48 38 48H10C4.47715 48 0 43.5228 0 38V10Z" fill="#00CEB6" fill-opacity="0.09"/>
                            </svg>                            
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Đang sử dụng</span>
                        <span style="font-weight: 600; font-size: 21px; color: #00ceb6;">{{ formatNumberToReadable($inuse['total_money']) }}</span>
                        <span>{{ $inuse['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.75 9H36.25C37.218 9 38 9.95759 38 11.1429V13.2857C38 14.471 37.218 15.4286 36.25 15.4286H11.75C10.782 15.4286 10 14.471 10 13.2857V11.1429C10 9.95759 10.782 9 11.75 9ZM11.75 17.5714H36.25V34.7143C36.25 37.0781 34.6805 39 32.75 39H15.25C13.3195 39 11.75 37.0781 11.75 34.7143V17.5714ZM18.75 22.9286C18.75 23.5179 19.1438 24 19.625 24H28.375C28.8563 24 29.25 23.5179 29.25 22.9286C29.25 22.3393 28.8563 21.8571 28.375 21.8571H19.625C19.1438 21.8571 18.75 22.3393 18.75 22.9286Z" fill="#00AAFF"/>
                            <path d="M0 10C0 4.47715 4.47715 0 10 0H38C43.5229 0 48 4.47715 48 10V38C48 43.5229 43.5228 48 38 48H10C4.47715 48 0 43.5228 0 38V10Z" fill="#219EE8" fill-opacity="0.09"/>
                            </svg>                            
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Chưa sử dụng</span>
                        <span style="font-weight: 600; font-size: 21px; color: #03a9fd;">{{ formatNumberToReadable($unused['total_money']) }}</span>
                        <span>{{ $unused['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#FF9500" fill-opacity="0.1"/>
                            <path d="M24.3323 9.22266C25.1212 9.22266 25.8491 9.66908 26.2491 10.4012L38.2499 32.306C38.6555 33.0441 38.6555 33.9548 38.2611 34.6929C37.8666 35.431 37.1276 35.8893 36.3331 35.8893H12.3314C11.5369 35.8893 10.798 35.431 10.4035 34.6929C10.009 33.9548 10.0146 33.0381 10.4146 32.306L22.4155 10.4012C22.8155 9.66908 23.5433 9.22266 24.3323 9.22266ZM24.3323 16.8417C23.5933 16.8417 22.9988 17.4786 22.9988 18.2703V24.9369C22.9988 25.7286 23.5933 26.3655 24.3323 26.3655C25.0712 26.3655 25.6657 25.7286 25.6657 24.9369V18.2703C25.6657 17.4786 25.0712 16.8417 24.3323 16.8417ZM26.1102 30.175C26.1102 29.6699 25.9229 29.1854 25.5894 28.8282C25.256 28.471 24.8038 28.2703 24.3323 28.2703C23.8607 28.2703 23.4085 28.471 23.0751 28.8282C22.7417 29.1854 22.5544 29.6699 22.5544 30.175C22.5544 30.6802 22.7417 31.1647 23.0751 31.5219C23.4085 31.8791 23.8607 32.0798 24.3323 32.0798C24.8038 32.0798 25.256 31.8791 25.5894 31.5219C25.9229 31.1647 26.1102 30.6802 26.1102 30.175Z" fill="#FF9500"/>
                            </svg>                            
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Hỏng</span>
                        <span style="font-weight: 600; font-size: 21px; color: #ff9500;">{{ formatNumberToReadable($broken['total_money']) }}</span>
                        <span>{{ $broken['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#FF4B4B" fill-opacity="0.13"/>
                            <g clip-path="url(#clip0_1_3)">
                            <path d="M24 40C28.2435 40 32.3131 38.3143 35.3137 35.3137C38.3143 32.3131 40 28.2435 40 24C40 19.7565 38.3143 15.6869 35.3137 12.6863C32.3131 9.68571 28.2435 8 24 8C19.7565 8 15.6869 9.68571 12.6863 12.6863C9.68571 15.6869 8 19.7565 8 24C8 28.2435 9.68571 32.3131 12.6863 35.3137C15.6869 38.3143 19.7565 40 24 40ZM19.5 22.5H28.5C29.3312 22.5 30 23.1687 30 24C30 24.8312 29.3312 25.5 28.5 25.5H19.5C18.6687 25.5 18 24.8312 18 24C18 23.1687 18.6687 22.5 19.5 22.5Z" fill="#FF4B4B"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_1_3">
                            <rect width="32" height="32" fill="white" transform="translate(8 8)"/>
                            </clipPath>
                            </defs>
                            </svg>                            
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Mất, hủy, thanh lý</span>
                        <span style="font-weight: 600; font-size: 21px; color: #ff4b4b;">{{ formatNumberToReadable($lost['total_money']) }}</span>
                        <span>{{ $lost['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-4 d-flex justify-content-center" style="margin-bottom: 15px;">
                <div class="d-flex" style="background: #fff; border-radius: 15px; padding: 10px 15px; width: 100%;">
                    <span class="d-flex" style="align-content: center; flex-direction: row; flex-wrap: wrap; margin-right: 15px;">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#7C7C7C" fill-opacity="0.08"/>
                            <path d="M21.1406 10.0254C22.3058 10.2067 23.096 11.2379 22.9018 12.3254L22.2455 16.0004H28.6138L29.3839 11.6692C29.5781 10.5817 30.683 9.84419 31.8482 10.0254C33.0134 10.2067 33.8036 11.2379 33.6094 12.3254L32.9598 16.0004H36.8571C38.0424 16.0004 39 16.8942 39 18.0004C39 19.1067 38.0424 20.0004 36.8571 20.0004H32.2433L30.817 28.0004H34.7143C35.8996 28.0004 36.8571 28.8942 36.8571 30.0004C36.8571 31.1067 35.8996 32.0004 34.7143 32.0004H30.1004L29.3304 36.3317C29.1362 37.4192 28.0312 38.1567 26.8661 37.9754C25.7009 37.7942 24.9107 36.7629 25.1049 35.6754L25.7612 32.0067H19.3929L18.6228 36.3379C18.4286 37.4254 17.3237 38.1629 16.1585 37.9817C14.9933 37.8004 14.2031 36.7692 14.3973 35.6817L15.0402 32.0004H11.1429C9.95759 32.0004 9 31.1067 9 30.0004C9 28.8942 9.95759 28.0004 11.1429 28.0004H15.7567L17.183 20.0004H13.2857C12.1004 20.0004 11.1429 19.1067 11.1429 18.0004C11.1429 16.8942 12.1004 16.0004 13.2857 16.0004H17.8996L18.6696 11.6692C18.8638 10.5817 19.9687 9.84419 21.1339 10.0254H21.1406ZM21.529 20.0004L20.1027 28.0004H26.471L27.8973 20.0004H21.529Z" fill="#696969"/>
                            </svg>                            
                    </span>
                    <div class="d-flex flex-column" >
                        <span style="font-weight: 600; font-size: 17px;">Khác</span>
                        <span style="font-weight: 600; font-size: 21px; color: #696969;">{{ formatNumberToReadable($other['total_money']) }}</span>
                        <span>{{ $other['amount_assets'] }} tài sản</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;">
                    <h5 class="mb-3" style="font-weight: 600;">Biểu đồ biến động giá trị tài sản</h5>
                    <span>Đơn vị: <span id="unitValueChart"></span> đồng</span>
                    <canvas x-data="chartComponent()" x-init="fetchDataAndInitChart()" id="barChart"></canvas>
                </div>
            </div>
            <div class="col-xxl-6 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;">
                    <h5 class="mb-3" style="font-weight: 600;">Biểu đồ chi phí vận hành tài sản</h5>
                    <span>Đơn vị: <span id="unitOperatingChart"></span> đồng</span>
                    <canvas x-data="chartOperatingCost()" x-init="fetchDataAndInitChart()" id="chartOperatingCost"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function chartComponent() {
      return {
        chart: null,
        chartData: {
          labels: [],
          datasets: [{
                label: 'Tổng giá trị tài sản',
                data: [],
                backgroundColor: '#682eec40',
                borderColor: '#682eec',
                borderWidth: 1
            },
            {
            label: "Tổng giá trị tài sản (line)",
            data: [],
            backgroundColor: "#fff",
            borderColor: "#682eec",
            borderWidth: 2,
            fill: false,
            type: "line",
            tension: 0.4,
            },
            ]
        },
        async fetchDataAndInitChart() {
            try {
                const response = await axios.get('/api/report/get-data-value-report');
                const data = response.data;

                this.chartData.labels = data.data.data.labels;
                this.chartData.datasets[0].data = data.data.data.values;
                this.chartData.datasets[1].data = data.data.data.values;
                
                document.getElementById('unitValueChart').innerText = data.data.unit;

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
                borderWidth: 1
            }]
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
</script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('overviewReport', () => ({
        
    }));
});
</script>
@endsection