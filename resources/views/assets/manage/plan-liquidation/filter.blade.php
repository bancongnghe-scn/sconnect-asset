<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" id="namecodePlanLiquidation" placeholder="Tên/mã kế hoạch" @keydown.enter="list(filters)">
        <svg class="position-absolute mr-3" style="right: 0;height: -webkit-fill-available;"
         width="14" height="14" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 20L15.514 15.506L20 20ZM18 9.5C18 11.7543 17.1045 13.9163 15.5104 15.5104C13.9163 17.1045 11.7543 18 9.5 18C7.24566 18 5.08365 17.1045 3.48959 15.5104C1.89553 13.9163 1 11.7543 1 9.5C1 7.24566 1.89553 5.08365 3.48959 3.48959C5.08365 1.89553 7.24566 1 9.5 1C11.7543 1 13.9163 1.89553 15.5104 3.48959C17.1045 5.08365 18 7.24566 18 9.5V9.5Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round"></path>
        </svg>
    </div>
    
    <div class="col-2">
        <select class="form-control select2" data-placeholder="Chọn trạng thái" id="statusPlanLiquidation"
            x-model="filters.status">
            <option value="" disabled selected>Trạng thái</option>
            <template x-for="(value, key) in listStatusPlanLiquidation">
                <option :value="key" x-text="value"></option>
            </template>
        </select>
    </div>

    <div class="col-3">
        @include('common.datepicker', ['placeholder' => "Ngày tạo", 'id' => "filterSigningDate"])
    </div>

    <div class="col-4 d-flex tw-gap-x-2 tw-justify-end">
        <button type="button" class="btn btn-sc" @click="handleShowModalCreatePlan()">
            Thêm mới
        </button>
        <button type="button" class="btn tw-bg-red-600 tw-text-white"  @click="confirmRemoveMultiplePlan" :disabled="window.checkDisableSelectRow">
            Xóa chọn
        </button>
    </div>
</div>

<script>
    $('#tableAssetPlanLiquidation #statusPlanLiquidation, #tableAssetPlanLiquidation #filterSigningDate').on('change', function () {
        let name_code = $('#tableAssetPlanLiquidation #namecodePlanLiquidation').val();
        let status = $('#tableAssetPlanLiquidation #statusPlanLiquidation').val();
        let created_at = $('#tableAssetPlanLiquidation #filterSigningDate').val();
        Alpine.store('assetPlanLiquidation').instance.list({
            page: 1,
            limit: 10,
            name_code: name_code,
            status: status,
            created_at: created_at
        });
    })
</script>