<div x-data="shoppingPlanCompanyYear">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tw-mt-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-3 align-items-end form-group">
                                    <div class="col-3 pl-0">
                                        @include('common.datepicker.datepicker_year',['model' => 'filters.time'])
                                    </div>
                                    <div class="col-2">
                                        @include('common.select_custom.simple.select_single', [
                                            'selected' => 'filters.status',
                                            'options' => 'STATUS_SHOPPING_PLAN_COMPANY',
                                            'placeholder' => 'Chọn trạng thái',
                                        ])
                                    </div>
                                    <div class="col-auto">
                                        <button @click="reloadPage()" type="button" class="btn btn-outline-danger">Xóa lọc</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('shopping_plan_company.crud')
                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="$('#idModalInsert').modal('show')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
                        </div>
                    @endcan

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
    @include('assets.shopping-plan-company.year.modalInsert')
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

    @include('assets.shopping-plan-company.year.detail')
    @include('assets.shopping-plan-company.year.update')
</div>
