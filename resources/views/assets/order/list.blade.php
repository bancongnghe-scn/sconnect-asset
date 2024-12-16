@extends('layouts.app',[
    'title' => 'Quản lý đơn hàng'
])

@section('content')
    <div x-data="order">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            @can('order.crud')
                <button type="button" class="btn btn-sc" @click="$('#modalUI').modal('show')">
                    Thêm mới
                </button>
                <button type="button" class="btn tw-bg-red-600 tw-text-white" @click="confirmRemoveMultiple"
                        :disabled="window.checkDisableSelectRow">
                    Xóa chọn
                </button>
            @endcan
        </div>

        <div>
            @include('assets.order.filters')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('common.table')
        </div>
    </div>
@endsection

@section('js')
    @vite(['resources/js/assets/order.js'])
@endsection
