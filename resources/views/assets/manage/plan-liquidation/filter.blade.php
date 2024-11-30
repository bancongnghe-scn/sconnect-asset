<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" id="namecodePlanLiquidation" placeholder="Tên/mã kế hoạch" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3" style="right: 0;height: -webkit-fill-available;scale:0.45;"></i>
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
