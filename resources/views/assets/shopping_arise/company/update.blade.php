@extends('layouts.app_v2',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('x-data')
    x-data="shopping_arise_company_update({{$id}})"
@endsection


@section('title_other')
    <div class="tw-h-fit">
        @include('component.status.status_shopping_arise', [
          'status' => 'data.status'
        ])
    </div>
@endsection

@section('btn-header')
    <template x-for="(config, key) in configButtons" :key="key">
        <template x-if="config.condition()">
            <template x-for="(button, index) in config.buttons" :key="key + index">
                <template x-if="!button.permission || permission.includes(button.permission)">
                    <button :class="button.class" @click="button.action()">
                        <span x-text="button.text"></span>
                    </button>
                </template>
            </template>
        </template>
    </template>
    <a class="btn btn-warning" href="/shopping-arise/list">Quay lại</a>
@endsection

@section('content')
    <div class="d-flex tw-gap-x-3 h-100">
        <div class="flex-grow-1 overflow-auto custom-scroll">
            @include('assets.shopping_arise.company.shopping_arise_info', ['action' => 'update'])
        </div>
        <div class="col-3 border border-right-0 border-top-0 border-bottom-0" x-data="{ id: {{$id}} }">
            @include('component.history_comment.history_comment', ['type' => 'TYPE_COMMENT_ORDER'])
        </div>
    </div>

    <div
        x-data="{
                modalId: 'modalConfirmSend',
                contentBody: 'Nếu có thay đổi đề xuất, hãy chắc chắn rằng bạn đã lưu đề xuất trước khi gửi duyệt !'
            }"
        @ok="managerSendShoppingArise"
    >
        @include('common.modal-confirm')
    </div>

    <div @ok="approvalShoppingAsset(statusDisapproval)">
        @include('common.modal-note', ['id' => 'modalNoteDisapproval', 'model' => 'note_disapproval'])
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/shopping_arise/company/shopping_arise_update.js',
        'resources/js/assets/api/apiAssetType.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiShoppingArise.js',
        'resources/js/app/api/apiJob.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/assets/api/apiShoppingAsset.js'
    ])
@endsection
