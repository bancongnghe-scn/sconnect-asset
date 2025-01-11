<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" id="namecodePlanLiquidation" placeholder="Tên/mã kế hoạch" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;" x-on:click="list(filters)"></i>
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
        <div class="input-group">
            <input type="text" class="form-control datepicker" id="filterSigningDatePlan"
                   placeholder="Tháng tạo" autocomplete="off">
            <span class="input-group-text">
                <i class="fa-regular fa-calendar-days"></i>
            </span>
        </div>
    </div>

    <div class="col-4 d-flex tw-gap-x-2 tw-justify-end">
        <button type="button" class="btn btn-sc" style="line-height: 20.6px;"  @click="handleShowModalCreatePlan()">
            Thêm mới
        </button>
        <button type="button" class="btn tw-bg-red-600 tw-text-white" style="line-height: 20.6px;" @click="confirmRemoveMultiplePlan" :disabled="window.checkDisableSelectRow">
            Xóa chọn
        </button>
    </div>
</div>
<style>
    /* Hiệu ứng khi hover */
    .fa-magnifying-glass:hover {
        cursor: pointer;
    }
</style>
