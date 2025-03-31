@extends('layouts.app',[
    'title' => 'Đề xuất mua sắm phát sinh'
])

@section('content')
    <div x-data="{tab: 'company'}">
        <div class="d-flex tw-gap-x-4 mb-3">
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="tab === 'company' ? 'active-link' : 'inactive-link'"
               @click="tab = 'company'"
            >
                Công ty
            </a>
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="tab === 'organization' ? 'active-link' : 'inactive-link'"
               @click="tab = 'organization'"
            >
                Của tôi
            </a>
        </div>

        <template x-if="tab === 'company'">
            <div x-data="shopping_arise_company_list">
                <div class="card card-body">
                    <div>
                        @include('assets.shopping_arise.company.filters')
                    </div>

                    <div class="mt-3">
                        @include('assets.shopping_arise.company.table')
                    </div>
                </div>
            </div>
        </template>
        <template x-if="tab === 'organization'">
            <div x-data="shopping_arise_organization_list">
                <div>
                    <div class="card card-body">
                        <div>
                            @include('assets.shopping_arise.organization.filters')
                        </div>

                        <div class="mt-3">
                            @include('assets.shopping_arise.organization.table')
                        </div>
                    </div>
                </div>

                {{--modal--}}
                @include('assets.shopping_arise.organization.modal_insert')
                <div
                    x-data="{
                modalId: 'modalConfirmDelete',
                contentBody: 'Bạn có chắc chắn muốn xóa đề xuất mua sắm này không ?'
            }"
                    @ok="deleteShoppingArise"
                >
                    @include('common.modal-confirm')
                </div>
            </div>
        </template>
    </div>
@endsection

@vite([
    'resources/js/assets/shopping_arise/company/shopping_arise_list.js',
    'resources/js/assets/api/apiShoppingArise.js',
    'resources/js/assets/api/apiAssetType.js',
    'resources/js/app/api/apiJob.js',
    'resources/js/assets/shopping_arise/organization/shopping_arise_list.js',
])
