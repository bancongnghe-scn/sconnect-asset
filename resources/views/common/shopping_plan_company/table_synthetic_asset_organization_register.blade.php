<div class="tw-max-h-dvh overflow-y-scroll">
    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
           aria-describedby="example2_info">
        <thead class="position-sticky z-1" style="top: -1px">
        <tr>
            <th rowspan="2" class="text-center">STT</th>
            <th rowspan="2" class="text-center">Đơn vị</th>
            <th rowspan="2" class="text-center">Loại tài sản</th>
            <th colspan="12" class="text-center">Số lượng đăng ký theo tháng</th>
            <th rowspan="2" class="text-center">Tổng Số lượng</th>
            <th rowspan="2" class="text-center">Tổng Thành tiền</th>
            <th rowspan="2" class="text-center">Thao tác</th>
        </tr>
        <tr>
            <th>T1</th>
            <th>T2</th>
            <th>T3</th>
            <th>T4</th>
            <th>T5</th>
            <th>T6</th>
            <th>T7</th>
            <th>T8</th>
            <th>T9</th>
            <th>T10</th>
            <th>T11</th>
            <th>T12</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="(organization,index) in data.organizations">
            <template x-if="organization.total_price !== 0">
                <template x-for="(assetRegister, stt) in organization.asset_register">
                    <tr>
                        <!-- Cột STT và tên tổ chức, chỉ xuất hiện cho hàng đầu tiên (stt === 0) -->
                        <td x-text="index + 1" :rowspan="stt === 0 ? organization.asset_register.length : null" x-show="stt === 0"></td>
                        <td x-text="organization.name" :rowspan="stt === 0 ? organization.asset_register.length : null" x-show="stt === 0" class="tw-font-bold"></td>

                        <!-- Cột loại tài sản -->
                        <td x-text="assetRegister.asset_type_name" class="text-center"></td>

                        <!-- Cột đăng ký -->
                        <template x-for="number in Array.from({ length: 12 }, (_, i) => i + 1)">
                            <td x-text="assetRegister.register[number - 1]" class="text-center">-</td>
                        </template>

                        <!-- Cột tổng đăng ký -->
                        <td x-text="assetRegister.total_register" class="text-center"></td>

                        <!-- Tổng giá của tổ chức, chỉ xuất hiện cho hàng đầu tiên (stt === 0) -->
                        <td x-text="organization.total_price" :rowspan="stt === 0 ? organization.asset_register.length : null" x-show="stt === 0" class="text-center"></td>

                        <!-- Cột cuối cùng, chỉ xuất hiện cho hàng đầu tiên (stt === 0) -->
                        <td :rowspan="stt === 0 ? organization.asset_register.length : null" x-show="stt === 0" class="text-center">...</td>
                    </tr>
                </template>

            </template>
            <template x-if="organization.total_price === 0">
                <tr>
                    <td x-text="index+1" rowspan="1"></td>
                    <td x-text="organization.name" rowspan="1" class="tw-font-bold"></td>
                    <template x-for="number in Array.from({ length: 14 }, (_, i) => i + 1)">
                        <td class="text-center">-</td>
                    </template>
                    <td x-text="organization.total_price" rowspan="1" class="text-center"></td>
                    <td rowspan="1" class="text-center">...</td>
                </tr>
            </template>
        </template>
        </tbody>
    </table>
</div>
