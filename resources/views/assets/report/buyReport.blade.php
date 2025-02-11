@extends('assets.report.baseReport')

@section('content')
@include('assets.report.components.tabBar')
    <div x-data="valueReport">
        <div class="row">
            <div class="col-xxl-12 col-sm-12">
                <div style="background: #fff; padding: 30px; border-radius: 15px;" x-data="fetchDataComponent()" x-init="fetchData()">
                    {{-- <h5 class="mb-3" style="font-weight: 600;">Bảng biến động giá trị tài sản qua các kỳ</h5> --}}
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">Kỳ</th>
                            <th class="text-center">Ngân sách theo kế hoạch</th>
                            <th class="text-center">Thực tế</th>
                            <th class="text-left">Chênh lệch</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Kế hoạch mua sắm quý 1 năm 2024</td>
                                <td class="text-center">600</td>
                                <td class="text-center">588</td>
                                <td class="text-left">Thực tế ít hơn kế hoạch 12 triệu</td>
                            </tr>
                            <tr>
                                <td class="text-center">Kế hoạch mua sắm quý 2 năm 2024</td>
                                <td class="text-center">600</td>
                                <td class="text-center">588</td>
                                <td class="text-left">Thực tế ít hơn kế hoạch 12 triệu</td>
                            </tr>
                            <tr>
                                <td class="text-center">Kế hoạch mua sắm quý 3 năm 2024</td>
                                <td class="text-center">566</td>
                                <td class="text-center">588</td>
                                <td class="text-left">Kế hoạch hơn thực tế 22 triệu</td>
                            </tr>
                            <tr>
                                <td class="text-center">Kế hoạch mua sắm quý 4 năm 2024</td>
                                <td class="text-center">600</td>
                                <td class="text-center">588</td>
                                <td class="text-left">Thực tế ít hơn kế hoạch 12 triệu</td>
                            </tr>
                            <tr>
                                <td class="text-center">Kế hoạch mua sắm năm 2024</td>
                                <td class="text-center">2,3 tỷ</td>
                                <td class="text-center">2,5 tỷ</td>
                                <td class="text-left">Kế hoạch hơn thực tế 200 triệu</td>
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