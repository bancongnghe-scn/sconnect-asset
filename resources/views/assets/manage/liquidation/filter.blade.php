<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;"></i>
    </div>
    <div class="col-2">
        <div class="row align-items-center">
            <div style="max-width: fit-content">
                <span>Đã chọn (<span id="numberLiquidation"></span>)</span>
            </div>
            <div style="max-width: fit-content">
                <button class="border-0 btn tw-text-red-400" @click="unselectedAllLiquidation()">Bỏ chọn</button>
            </div>
        </div>
    </div>
    <div class="col-2">
        <button type="button" style="border:1px solid #ddd;" class="btn tw-bg-green-500 tw-text-white" x-show="showAction.create ?? true" @click="$dispatch('create')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-plus" style="">&#xF117;</i>
            <span>
                Tạo kế hoạch đề xuất
            </span>
        </button>
    </div>
</div>
