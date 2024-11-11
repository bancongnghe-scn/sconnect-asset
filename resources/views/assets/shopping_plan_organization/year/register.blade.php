<?php
@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm 2024'
])

@section('content')
    <div x-data="registerYear">
        <div class="mb-3 d-flex gap-2 justify-content-end">
            <template x-if="+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER
                || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED ">
                <button class="btn btn-primary" @click="saveRegister">Đăng ký</button>
            </template>
            <button class="btn btn-warning" @click="window.location.href = `/shopping-plan-company/year/list`">Quay lại</button>
        </div>
        <div class="d-flex justify-content-between">
            <div class="card tw-w-[78%]">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex tw-gap-x-4 mb-3">
                            <div class="active-link tw-w-fit">Thông tin chung</div>
                            <span x-text="STATUS_SHOPPING_PLAN_ORGANIZATION[data.status]" class="p-1 border rounded"
                                  :class="{
                                             'tw-text-sky-600 tw-bg-sky-100': +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_NEW,
                                             'tw-text-purple-600 tw-bg-purple-100': +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
                                             'tw-text-green-600 tw-bg-green-100': +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                                             || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                                             'tw-text-green-900 tw-bg-green-100'  : +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                                             || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                                             || +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL,
                                             'tw-text-red-600 tw-bg-red-100'  : +data[key] === STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                                  }"
                            ></span>
                        </div>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-font-bold">Tên</label>
                                <input type="text" class="form-control yearPicker" x-model="data.name" autocomplete="off" disabled>
                            </div>

                            <div>
                                <label class="tw-font-bold">Đơn vị</label>
                                <input type="text" class="form-control" x-model="data.organization_name" disabled>
                            </div>

                            <div>
                                <label class="tw-font-bold">Thời gian đăng ký</label>
                                <input type="text" class="form-control dateRange" id="selectDateRegister"
                                       placeholder="Chọn thời gian đăng ký" autocomplete="off" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card tw-w-[20%]">
                <div class="container d-flex tw-gap-x-4 mt-3">
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="activeLink.history ? 'active-link' : 'inactive-link'"
                       @click="handleShowActive('history')"
                    >
                        Lịch sử
                    </a>
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="activeLink.comment ? 'active-link' : 'inactive-link'"
                       @click="handleShowActive('comment')"
                    >
                        Bình luận
                    </a>
                </div>
                <div class="mt-3" style="border-top: 1px solid">
                    <div class="container mt-3" x-show="activeLink.history">
                        <template x-for="log in logs">
                            <div class="card">
                                <p class="mb-0" x-text="log.created_at"></p>
                                <div>
                                    <p class="tw-inline tw-font-bold" x-text="log.created_by + ': '"></p>
                                    <span x-text="log.desc"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="container mt-3" id="comment" x-show="activeLink.comment">
                        <template x-for="comment in comments">
                            <div class="card">
                                <p class="mb-0" x-text="comment.created_at"></p>
                                <div>
                                    <p class="tw-inline tw-font-bold" x-text="comment.user_name + ': '"></p>
                                    <span x-text="comment.message"></span>
                                </div>
                            </div>
                        </template>
                        <button @click="sentComment">Gửi tin nhắn</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([

    ])
@endsection
