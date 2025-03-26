@extends('layouts.app',[
    'title' => 'Tài sản'
])

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" x-data="{tab: 'inventory'}">
                        <div class="d-flex tw-gap-x-4">
                            <a class="tw-no-underline hover:tw-text-green-500"
                               :class="tab === 'my_user' ? 'active-link' : 'inactive-link'"
                               @click="tab = 'my_user'"
                            >
                                Của tôi
                            </a>
                            <a class="tw-no-underline hover:tw-text-green-500"
                               :class="tab === 'inventory' ? 'active-link' : 'inactive-link'"
                               @click="tab = 'inventory'"
                            >
                                Kiểm kê
                            </a>
                        </div>

                        <div class="mt-3">
                            <template x-if="tab === 'my_user'"></template>
                            <template x-if="tab === 'inventory'">
                                @include('assets.asset.user.inventory.list')
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @vite([
       'resources/js/assets/api/apiPlanInventory.js'
    ])
@endsection
