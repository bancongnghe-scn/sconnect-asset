<div x-data="shoppingPlanCompanyYear">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('assets.shopping-plan-company.year.filters')

                    <div
                        @change-page.window="changePage($event.detail.page)"
                        @change-limit.window="changeLimit"
                    >
                        @include('assets.shopping-plan-company.year.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  modal--}}
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

    @include('assets.shopping-plan-company.year.modalInsert')
    @include('assets.shopping-plan-company.year.update')
    <div x-data="{id: null}" x-effect="id = idPlanOrganization">
        @include('assets.shopping_plan_organization.year.detail', ['id' => 'modalOrganizationCompany'])
    </div>
</div>

