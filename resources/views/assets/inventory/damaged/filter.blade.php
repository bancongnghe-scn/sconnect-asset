<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;cursor: pointer;" x-on:click="list(filters)"></i>
    </div>

    <div class="col-9 text-right">
        <button type="button" class="btn tw-bg-orange-400 tw-text-white" style="line-height: 20.6px;" 
            @click="modalRepairMultiUI" 
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Sửa chữa
            </span>
        </button>
        <button type="button" class="btn tw-bg-red-400 tw-text-white" style="line-height: 20.6px;" 
            @click="modalCancelMultiUI"
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Hủy
            </span>
        </button>
        <button type="button" class="btn tw-bg-yellow-400 tw-text-white" style="line-height: 20.6px;" 
            @click="modalLiquidationMultiUI"
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Đề nghị thanh lý
            </span>
        </button>
    </div>
</div>
