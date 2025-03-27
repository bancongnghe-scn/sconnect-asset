<div x-data="shoppingPlanCompanyWeek">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        @include('assets.shopping-plan-company.week.filter')
                    </div>

                    <div
                        @remove="confirmRemove($event.detail.id)">
                        @include('assets.shopping-plan-company.week.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  modal--}}
    @include('assets.shopping-plan-company.week.modalInsert')
    @include('assets.shopping-plan-company.week.update')
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
</div>
