@extends('assets.report.baseReport')

@section('content')
@include('assets.report.components.tabBar')
    <div x-data="valueReport">
        <div class="row">
            <div class="col-xxl-12 col-sm-12" style="margin-bottom: 20px;">
                <div style="background: #fff; padding: 30px; border-radius: 15px;" x-data="fetchDataComponent()" x-init="fetchData()">
                    <h5 class="mb-3" style="font-weight: 600;">Bảng báo cáo nhà cung cấp</h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Nhà cung cấp</th>
                            <th class="text-center">Số lượng tài sản đã mua</th>
                            <th class="text-center">Số đợt đánh giá</th>
                            <th class="text-left">Chi tiết các đợt đánh giá</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Thế giới di động</td>
                                <td class="text-center">56</td>
                                <td class="text-center">2</td>
                                <td class="text-left">Tốt: 1 đợt, Bình thường: 1 đợt</td>
                            </tr>
                            <tr>
                                <td class="text-center">Điện máy xanh</td>
                                <td class="text-center">22</td>
                                <td class="text-center">2</td>
                                <td class="text-left">Tốt: 1 đợt, Bình thường: 1 đợt</td>
                            </tr>
                            <tr>
                                <td class="text-center">Nội thất Hòa Phát</td>
                                <td class="text-center">34</td>
                                <td class="text-center">5</td>
                                <td class="text-left">Tốt: 1 đợt, Bình thường: 2 đợt, Kém: 2 đợt</td>
                            </tr>
                        </tbody>
                  </table>
                </div>
            </div>

            <div class="col-xxl-12 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;" x-data="fetchDataComponent()" x-init="fetchData()">
                    <h5 class="mb-3" style="font-weight: 600;">Bảng báo cáo nhà cung cấp</h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Nhà cung cấp</th>
                            <th class="text-center">Tổng giá trị</th>
                            <th class="text-center">Số tài sản</th>
                            <th class="text-left">Chi tiết</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Thế giới di động</td>
                                <td class="text-center">2 tỷ</td>
                                <td class="text-center">245</td>
                                <td class="text-left">200 case máy tính, 45 màn hình</td>
                            </tr>
                            <tr>
                                <td class="text-center">Điện máy xanh</td>
                                <td class="text-center">1,3 tỷ</td>
                                <td class="text-center">433</td>
                                <td class="text-left">150 case máy tính, 100 laptop, 100 chuột, 83 bàn phím</td>
                            </tr>
                            <tr>
                                <td class="text-center">Nội thất Hòa Phát</td>
                                <td class="text-center">900 triệu</td>
                                <td class="text-center">133</td>
                                <td class="text-left">100 bàn làm việc, 33 ghế xoay</td>
                            </tr>
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
    function fetchDataComponent() {
    return {
        // title: '',
        labels: '',
        arrDifference: [],
        values: [],

        async fetchData() {
            try {
                const response = await axios.get('/api/report/get-data-value-report');
                const data = response.data;

                // this.title = response.data.title;
                // this.description = response.data.description;
                this.values = data.data.data.values;
                this.labels = data.data.data.labels;
                this.arrDifference = data.data.data.arrDifference;
                console.log( data.data.data);
            } catch (error) {
                console.error('Lỗi khi gọi API:', error);
            }
        }
    };
}
</script>
<script>
    document.addEventListener('alpine:init', () => {
    Alpine.data('valueReport', () => ({

    }));
});
</script>
@endsection