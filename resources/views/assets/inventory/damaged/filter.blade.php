<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <i class="fa-solid fa-magnifying-glass position-absolute mr-3 tw-right-0 tw-w-3" style="height: -webkit-fill-available;cursor: pointer;" x-on:click="list(filters)"></i>
    </div>

    <div class="col-9 text-right">
        <button type="button" class="btn tw-text-white border-0" style="line-height: 20.6px;background-color: #379237;" 
            @click="modalRepairMultiUI" 
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Sửa chữa
            </span>
        </button>
        <button type="button" class="btn tw-text-white border-0" style="line-height: 20.6px; background-color:rgb(232 65 65);" 
            @click="modalCancelMultiUI"
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Hủy
            </span>
        </button>
        <button type="button" class="btn tw-text-white border-0" style="line-height: 20.6px;background-color: #ff953c;" 
            @click="modalLiquidationMultiUI"
            x-show="true" :disabled="window.checkDisableSelectRow">
            <span>
                Đề nghị thanh lý
            </span>
        </button>
    </div>
</div>
