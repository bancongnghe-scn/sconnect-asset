<table id="example2" class="table table-bordered dataTable dtr-inline"
       aria-describedby="example2_info">
    <thead>
    <tr>
        <th rowspan="2" colspan="1" class="text-center">Mã tài sản</th>
        <th rowspan="2" colspan="1" class="text-center">Tên tài sản</th>
        <th rowspan="2" colspan="1" class="text-center">Số seri</th>
        <th rowspan="2" colspan="1" class="text-center">Loại tài sản</th>
        <th rowspan="2" colspan="1" class="text-center">Trạng thái kiểm kê</th>
        <th colspan="6" class="text-center">Sổ sách</th>
    </tr>
    <tr>
        <th class="text-center">SL</th>
        <th class="text-center">Đơn vị</th>
        <th class="text-center">Người sử dụng</th>
        <th class="text-center">Người đại diện</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Đối tượng sử dụng</th>
    </tr>
    </thead>
    <tbody>
    <template x-for="asset in data.assets"></template>
        <tr>
            <td x-text="asset.code"></td>
            <td x-text="asset.name"></td>
            <td x-text="asset.seri_number"></td>
            <td x-text="listAssetType.find(item => item.asset_type_id = asset.asset_type_id)?.name"></td>
            <td x-text="LIST_STATUS_PLAN_INVENTORY_ASSET[STATUS_ASSET_NOT_INVENTORIED]"></td>
            <td>1</td>
            <td></td>
            <td></td>
            <td></td>
            <td x-text="LIST_STATUS_ASEET[asset.status]"></td>
            <td></td>
        </tr>
    </tbody>
</table>
