<div x-data="allocation_rate_organization">
    <div class="d-flex justify-content-between">
        <div class="d-flex col-6 pl-0">
            <div class="col-5 pl-0">
                @include('common.select_custom.extent.select_single', [
                     'selected' => 'filters.organization_id',
                     'options' => 'listOrganization',
                     'placeholder' => 'Chọn đơn vị',
                ])
            </div>
            <div class="col-auto">
                <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
            </div>
        </div>
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModal('create')">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button" @click="handleShowModalConfirmRemove()" :disabled="window.checkDisableSelectRow">
                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
            </button>
        </div>
    </div>
    <div class="mt-3">
        @include('assets.allocation_rate.organization.table')
    </div>
    @include('assets.allocation_rate.organization.modalUI')
    <div
        x-data="{
            modalId: 'modalConfirmRemoveOrganization',
            contentBody: 'Bạn có chắc chắn muốn xóa định mức cấp phát này không ?'
        }"
        @ok="remove"
    >
        @include('common.modal-confirm')
    </div>
</div>
@vite([
    'resources/js/assets/allocation_rate/allocation_rate_organization.js'
])
