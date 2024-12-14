@extends('layouts.app',[
    'title' => 'Quản lý đơn hàng'
])

@section('content')
    <div x-data="order">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            @can('shopping_plan_company.crud')
                <button type="button" class="btn btn-sc" @click="$('#modalUI').modal('show')">
                    Thêm mới
                </button>
                <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmRemoveMultiple"
                        :disabled="window.checkDisableSelectRow">
                    Xóa chọn
                </button>
            @endcan
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body d-flex flex-row align-items-end form-group">
                            <div class="col-2">
                                <label class="tw-font-bold">Mã đơn hàng</label>
                                <input class="form-control" type="text" x-model="filters.code">
                            </div>
                            <div class="col-3">
                                <label class="tw-font-bold">Ngày đơn hàng</label>
                                @include('common.datepicker.datepicker', ['placeholder' => "Ngày đơn hàng", 'model' => "filters.created_at"])
                            </div>
                            <div class="col-2">
                                <label class="tw-font-bold">Trạng thái</label>
                                <div x-data="{
                                model: filters.status,
                                init() {this.$watch('filters.status', (newValue) => {console.log(filters.status); if (this.model !== newValue) {this.model = newValue}})}
                            }"
                                     @select-change="filters.status = $event.detail">
                                    @include('common.select2.simple.select2_single', [
                                          'placeholder' => 'Chọn trạng thái',
                                          'values' => 'LIST_STATUS_ORDER'
                                    ])
                                </div>
                            </div>
                            <div class="col-auto">
                                <button @click="list(filters)" type="button" class="btn btn-block btn-sc">Tìm kiếm</button>
                            </div>
                            <div class="col-auto">
                                <button @click="reloadPage()" type="button" class="btn btn-secondary">Xóa lọc</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite(['resources/js/assets/order.js'])
@endsection
