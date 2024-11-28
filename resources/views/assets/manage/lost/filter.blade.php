<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3" style="right: 0;height: -webkit-fill-available;scale:0.45;"></i>
    </div>
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
    <div class="col-1">
        <button type="button" class="btn tw-bg-green-500 tw-text-white" x-show="showAction.back ?? true" @click="$dispatch('back')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-arrow-rotate-left">&#xF117;</i>
            <span>
                Tìm thấy
            </span>
        </button>
    </div>
    <div class="col-2">
        <button type="button" class="btn tw-bg-red-500 tw-text-white" x-show="showAction.cancel ?? true" @click="$dispatch('cancel')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-arrow-rotate-left">&#xF117;</i>
            <span>
                Hủy tài sản
            </span>
        </button>
    </div>
</div>
