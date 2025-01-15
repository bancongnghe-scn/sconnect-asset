@extends('layouts.app',[
    'title' => 'Ngành hàng'
])

@section('content')
    <div x-data="industry">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tw-mt-8">
                            @include('assets.industry.filters')
                        </div>

                        <div class="tw-mb-3 d-flex tw-gap-x-2 tw-justify-end">
                            <button class="btn btn-sc btn-sm px-3" type="button" @click="handleShowModalUI('create')">
                                <span>+ Thêm</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" type="button" @click="confirmRemoveMultiple" :disabled="window.checkDisableSelectRow">
                                <span><i class="fa-solid fa-trash-can pr-1"></i>Xóa chọn</span>
                            </button>
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
                </div>
            </div>
        </div>

        {{-- modal--}}
        <div>
            <div
                @save-industry="handleUI">
                @include('assets.industry.modalUI')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa ngành hàng này không ?'
            }"
                @ok="remove"
            >
                @include('common.modal-confirm')
            </div>

            <div
                x-data="{
                modalId: idModalConfirmDeleteMultiple,
                contentBody: 'Bạn có chắc chắn muốn xóa danh sách ngành hàng này không ?'
            }"
                @ok="removeMultiple"
            >
                @include('common.modal-confirm')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/industry.js',
        'resources/js/assets/api/apiIndustry.js',
    ])
@endsection
