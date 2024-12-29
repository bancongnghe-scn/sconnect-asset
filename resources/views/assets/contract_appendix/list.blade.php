<div x-data="appendix">
    <div class="row">
        <div class="col-12">
            <div class="tw-mt-8">
                @include('assets.contract_appendix.filter')
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
                @include('assets.contract_appendix.table')
            </div>
        </div>
    </div>

    {{--  modal--}}

    @include('assets.contract_appendix.modalUI')
    @include('assets.contract_appendix.modalInfo')

    <div
        x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa phụ lục hợp đồng này không ?'
            }"
        @ok="remove"
    >
        @include('common.modal-confirm')
    </div>

    <div
        x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách phụ lục hợp đồng này không ?'
            }"
        @ok="removeMultiple"
    >
        @include('common.modal-confirm')
    </div>
</div>

