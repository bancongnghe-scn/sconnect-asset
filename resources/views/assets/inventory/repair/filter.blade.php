<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản"  @keydown.enter="list(filters)"
        >
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3" style="right: 0;height: -webkit-fill-available;scale:0.45;"></i>
    </div>

    {{-- <div class="col-2">
        <select class="form-control select2" data-placeholder="Chọn trạng thái" id="statusPlanLiquidation"
            x-model="filters.status">
            <option value="" disabled selected>Trạng thái</option>
            <template x-for="(value, key) in listStatus">
                <option :value="key" x-text="value"></option>
            </template>
        </select>
    </div> --}}

    <div class="col-2">
        <div class="row align-items-center">
            <div style="max-width: fit-content">
                <span>Đã chọn (<span id="numberShow"></span>)</span>
            </div>
            <div style="max-width: fit-content">
                <button class="border-0 btn tw-text-red-400" @click="unselectedAll()">Bỏ chọn</button>
            </div>
        </div>
    </div>

    <div class="col-2">
        <button type="button" class="btn tw-bg-green-500 tw-text-white" x-show="showAction.complete ?? true" @click="$dispatch('complete')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-check"></i>
            <span>
                Hoàn thành
            </span>
        </button>
    </div>
</div>
