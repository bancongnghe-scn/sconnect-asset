<div x-data="shoppingPlanCompanyQuarter">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('assets.shopping-plan-company.quarter.filter')
                    </div>

                    @can('shopping_plan_company.crud')
                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModal('create')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
                        </div>
                    @endcan

                    <div
                        @remove="confirmRemove($event.detail.id)"
                        @change-page.window="changePage($event.detail.page)"
                        @change-limit.window="changeLimit"
                    >
                        @include('assets.shopping-plan-company.quarter.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  modal--}}
    @include('assets.shopping-plan-company.quarter.modalInsert')
    @include('assets.shopping-plan-company.quarter.detail')
    @include('assets.shopping-plan-company.quarter.update')
    <div x-data="{registers: [], data: []}" x-effect="registers = registersOrganization, data = dataOrganization">
        @include('assets.shopping_plan_organization.quarter.detail')
    </div>
    <div
        x-data="{
              modalId: 'idModalConfirmDelete',
              contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch mua sắm này không ?'
        }"
        @ok="remove"
    >
        @include('common.modal-confirm')
    </div>

    <div
        x-data="{
             modalId: 'idModalConfirmDeleteMultiple',
             contentBody: 'Bạn có chắc chắn muốn xóa danh sách kế hoạch mua sắm này không ?'
        }"
        @ok="removeMultiple"
    >
        @include('common.modal-confirm')
    </div>


    <div @ok="accountApprovalShoppingPlanOrganization(id_organization, ORGANIZATION_TYPE_DISAPPROVAL)">
        @include('common.modal-note', ['id' => 'modalNoteDisapproval', 'model' => 'note_disapproval'])
    </div>

    <div @ok="accountApprovalMultipleShoppingPlanOrganization(ORGANIZATION_TYPE_DISAPPROVAL)">
        @include('common.modal-note', ['id' => 'modalNoteDisapprovalMultiple', 'model' => 'note_disapproval'])
    </div>

    <div @ok="generalApprovalShoppingPlanCompany(GENERAL_TYPE_DISAPPROVAL_COMPANY)">
        @include('common.modal-note', ['id' => 'modalNoteDisapprovalPlanCompany', 'model' => 'note_disapproval'])
    </div>
</div>

