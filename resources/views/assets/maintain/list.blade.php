@extends('layouts.app',[
    'title' => 'Bảo dưỡng tài sản'
])

@section('content')
    <div x-data="maintain">
        <div class="d-flex tw-gap-x-4"
             x-data="{total_need_maintain: 0, total_plan_maintain: 0, total_maintaining: 0}"
        >
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.need_maintain ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('need_maintain')"
               @total-need-maintain.window="total_need_maintain = $event.detail"
               x-text="`Tài sản cần bảo dưỡng (${total_need_maintain})`"
            ></a>
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.plan ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('plan')"
               @total-plan-maintain.window="total_plan_maintain = $event.detail"
               x-text="`Kế hoạch bảo dưỡng (${total_plan_maintain})`"
            ></a>
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.maintaining ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('maintaining')"
               @total-maintain.window="total_maintaining = $event.detail"
               x-text="`Tài sản đang bảo dưỡng (${total_maintaining})`"
            ></a>
        </div>
        <hr>
        <div class="mt-3">
            <div x-show="activeLink.need_maintain">
                @include('assets.maintain.need_maintain.list')
            </div>
            <div x-show="activeLink.plan">
                @include('assets.maintain.plan_maintain.list')
            </div>
            <div x-show="activeLink.maintaining">
                @include('assets.maintain.maintaining.list')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
        'resources/js/assets/maintain/maintain.js',
        'resources/js/assets/api/maintain/apiMaintain.js'
    ])
@endsection

