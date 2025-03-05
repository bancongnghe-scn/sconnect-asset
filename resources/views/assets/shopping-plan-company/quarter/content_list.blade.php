<div x-data="shoppingPlanCompanyQuarter">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('assets.shopping-plan-company.quarter.filter')

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
    @include('assets.shopping-plan-company.quarter.update')
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

