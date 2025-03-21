<div x-data="plan_maintain">
    <div>
        @include('assets.maintain.plan_maintain.filters')
    </div>

    <div class="mt-3">
        @include('assets.maintain.plan_maintain.table')
    </div>

    @include('assets.maintain.plan_maintain.modalInsert')
    @include('assets.maintain.plan_maintain.modalUpdate')
    @include('assets.maintain.plan_maintain.modalConfirmCompletePlan')
    <div
        x-data="{
                modalId: 'modalConfirmDelete',
                contentBody: 'Bạn có chắc chắn muốn hủy kế hoạch bảo dưỡng này không ?'
            }"
        @ok="deletePlanMaintain"
    >
        @include('common.modal-confirm')
    </div>
</div>
@vite([
    'resources/js/assets/maintain/plan-maintain/plan_maintain.js',
    'resources/js/app/api/apiOrganization.js',
    'resources/js/assets/api/apiSupplier.js',
    'resources/js/app/api/apiUser.js'
])
