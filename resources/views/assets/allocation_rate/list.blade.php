@extends('layouts.app',[
    'title' => 'Định mức cấp phát'
])

@section('content')
    <div x-data="allocation_rate">
        <div class="d-flex tw-gap-x-4">
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.position ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('position')"
            >
                Chức danh
            </a>
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.organization ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('organization')"
            >
                Đơn vị
            </a>
        </div>
        <hr>
        <div class="mt-3">
            <div x-show="activeLink.position">
                @include('assets.allocation_rate.position.list')
            </div>
            <div x-show="activeLink.organization">
                @include('assets.allocation_rate.organization.list')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/allocation_rate/allocation_rate.js',
        'resources/js/assets/api/allocation_rate/apiAllocationRate.js',
        'resources/js/app/api/apiOrganization.js',
        'resources/js/assets/api/apiAssetType.js',
    ])
@endsection

