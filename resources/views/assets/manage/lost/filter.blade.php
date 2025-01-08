<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;" x-on:click="list(filters)"></i>
    </div>
    <div class="col-2">
        <div class="row align-items-center">
            <div class="d-flex">
                <div class="align-content-center mr-3" style="min-height: 35px;">
                    <span>Đã chọn (<span id="numberShow">0</span>)</span>
                </div>
                <div>
                    <button x-show="showButton" class="border-0 btn tw-text-red-400" @click="unselectedAll()">Bỏ chọn</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7 text-right">
        <button type="button" class="btn tw-bg-green-500 tw-text-white" x-show="showAction.back ?? true" @click="$dispatch('back')" :disabled="window.checkDisableSelectRow">
            <span>
                Tìm thấy
            </span>
        </button>
        <button type="button" class="btn tw-bg-red-500 tw-text-white" x-show="showAction.cancel ?? true" @click="$dispatch('cancel')" :disabled="window.checkDisableSelectRow">
            <span>
                Hủy tài sản
            </span>
        </button>
    </div>
</div>
<style>
    /* Hiệu ứng khi hover */
    .fa-magnifying-glass:hover {
        cursor: pointer;
    }
</style>