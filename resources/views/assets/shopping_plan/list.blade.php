@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm'
])

@section('content')
    <div x-data="shoppingPlan">
        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
            <button type="button" class="btn btn-sc" @click="handleShowModalUI('create')">
                Thêm mới
            </button>
        </div>

        <div>
            @include('assets.shopping_plan.filter')
        </div>

        <div
            @edit="handleShowModalUI('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @view="handleShowModalInfo($event.detail.id)"
            @change-page.window="changePage($event.detail.page)"
            @change-limit.window="changeLimit"
        >
            @include('assets.shopping_plan.table')
        </div>

        {{--  modal--}}
        <div>
            <div
            @include('assets.shopping_plan.modalUI')
        </div>

{{--        <div--}}
{{--            x-data="{--}}
{{--                modalId: idModalConfirmDelete,--}}
{{--                contentBody: 'Bạn có chắc chắn muốn xóa kế hoạch mua sắm này không ?'--}}
{{--            }"--}}
{{--            @ok="remove"--}}
{{--        >--}}
{{--            @include('common.modal-confirm')--}}
{{--        </div>--}}

{{--        <div>--}}
{{--            @include('assets.shopping_plan.modalInfo')--}}
{{--        </div>--}}
    </div>

@endsection

@section('js')
    @vite([
        'resources/js/assets/shoppingPlan.js',
    ])
@endsection
