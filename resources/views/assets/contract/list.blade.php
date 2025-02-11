<div x-data="contract">
    <div class="row">
        <div class="tw-mt-8">
            @include('assets.contract.filters')
        </div>

        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
                <span>+ Thêm</span>
            </button>
            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
            </button>
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.contract.table')
        </div>
    </div>

    {{-- modal--}}
    @include('assets.contract.modalUI')

    @include('assets.contract.modalInfo')

    <div
        x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa hợp đồng này không ?'
            }"
        @ok="remove"
    >
        @include('common.modal-confirm')
    </div>

    <div
        x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách hợp đồng này không ?'
            }"
        @ok="removeMultiple"
    >
        @include('common.modal-confirm')
    </div>

</div>
