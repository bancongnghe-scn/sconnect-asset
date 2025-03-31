<div>
    <div>
        <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
        <div class="tw-grid tw-grid-cols-3 tw-gap-x-3 mb-3">
            <div>
                <label>Ngày đề xuất</label>
                <input class="form-control" x-model="formatDateVN(data?.created_at)" disabled>
            </div>
            <div>
                <label>Người đề xuất</label>
                <input class="form-control" x-model="data.user_name" disabled>
            </div>
            <div>
                <label>Đơn vị đề xuất</label>
                <input class="form-control" x-model="data.organization_name" disabled>
            </div>
        </div>
        <div>
            <label>Nội dung</label>
            <textarea class="form-control" x-model="data.name" placeholder="Nhập nội dung mua sắm" disabled></textarea>
        </div>
    </div>

    <div class="mt-3">
        <div class="mb-3 active-link tw-w-fit">Chi tiết kế hoạch</div>
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
             <template x-if="+data.status === STATUS_SHOPPING_ARISE_PENDING_PROCESSING">
                 @include('assets.shopping_arise.company.table_asset_register')
             </template>
        </div>
    </div>
</div>
