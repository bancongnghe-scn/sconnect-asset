<div class="d-flex align-items-end mt-3 mb-3">
    <div class="col-3 d-flex position-relative">
        <input type="text" class="form-control" x-model="filters.name_code" placeholder="Tên/mã tài sản" @keydown.enter="list(filters)">
        <svg class="position-absolute mr-3" style="right: 0;height: -webkit-fill-available;"
         width="14" height="14" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 20L15.514 15.506L20 20ZM18 9.5C18 11.7543 17.1045 13.9163 15.5104 15.5104C13.9163 17.1045 11.7543 18 9.5 18C7.24566 18 5.08365 17.1045 3.48959 15.5104C1.89553 13.9163 1 11.7543 1 9.5C1 7.24566 1.89553 5.08365 3.48959 3.48959C5.08365 1.89553 7.24566 1 9.5 1C11.7543 1 13.9163 1.89553 15.5104 3.48959C17.1045 5.08365 18 7.24566 18 9.5V9.5Z" stroke="#7E7E7E" stroke-width="2" stroke-linecap="round"></path>
        </svg>
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
        <button type="button" style="background-color: #dff7e9!important;" class="btn tw-text-black" x-show="showAction.back ?? true" @click="$dispatch('back')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-arrow-rotate-left" style="color: #28c76f;">&#xF117;</i>
            <span>
                Tìm thấy
            </span>
        </button>
    </div>
    <div class="col-2">
        <button type="button" style="background-color: #fce5e6 !important;" class="btn tw-text-black" x-show="showAction.cancel ?? true" @click="$dispatch('cancel')" :disabled="window.checkDisableSelectRow">
            <i class="fa-solid fa-arrow-rotate-left" style="color: #ea5455;">&#xF117;</i>
            <span>
                Hủy tài sản
            </span>
        </button>
    </div>
</div>
