<div>
    <div>
        <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
        <div class="tw-grid tw-grid-cols-3 tw-gap-x-3 mb-3">
            <div>
                <label>Ngày đề xuất</label>
                <input class="form-control" x-model="formatDateVN(data?.created_at)" disabled>
            </div>
            <div>
                <label>Người đề xuất</label>
                <input class="form-control" x-model="data.user_name" disabled>
            </div>
            <div>
                <label>Đơn vị đề xuất</label>
                <input class="form-control" x-model="data.organization_name" disabled>
            </div>
        </div>
        <div>
            <label>Nội dung</label>
            <textarea class="form-control" x-model="data.name" placeholder="Nhập nội dung mua sắm" disabled></textarea>
        </div>
    </div>

    <div class="mt-3">
        <template x-if="[STATUS_SHOPPING_ARISE_PENDING_PROCESSING, STATUS_SHOPPING_ARISE_HR_PROCESSING].includes(+data.status)">
            <div>
                <div class="mb-3 active-link tw-w-fit">Chi tiết kế hoạch</div>
                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    @include('assets.shopping_arise.company.table_asset_register')
                </div>
            </div>
        </template>

        <template x-if="![STATUS_SHOPPING_ARISE_PENDING_PROCESSING, STATUS_SHOPPING_ARISE_HR_PROCESSING].includes(+data.status)">
            <div x-data="{tab: 'new'}">
                {{-- button phe duyet--}}
                <div>
                    <template x-for="(config, key) in configButtonsApproval" :key="key">
                        <template x-if="config.condition()">
                            <template x-if="!config.permission || permission.includes(config.permission)">
                                <div class="d-flex tw-gap-x-2 justify-content-end">
                                    <template x-for="(button, index) in config.buttons" :key="key + index">
                                        <button :class="button.class"
                                                x-text="button.text"
                                                @click="button.action()" :disabled="button.disabled()">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </template>
                </div>

                <div class="d-flex tw-gap-x-4 mb-3">
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="tab === 'new' ? 'active-link' : 'inactive-link'"
                       @click="tab = 'new'"
                       x-text="`Danh sách tài sản mua sắm (${assetSynthetic?.new?.length})`"
                    ></a>
                    <a href="#" class="tw-no-underline hover:tw-text-green-500"
                       :class="tab === 'rotation' ? 'active-link' : 'inactive-link'"
                       @click="tab = 'rotation'"
                       x-text="`Tài sản luân chuyển (${assetSynthetic?.rotation?.length})`"
                    ></a>
                </div>
                <div>
                    <div x-show="tab === 'new'" class="table-responsive custom-scroll">
                        @include('assets.shopping_arise.company.table_synthetic_action_new', ['action' => $action])
                    </div>
                    <div x-show="tab === 'rotation'" class="table-responsive custom-scroll">
                        @include('assets.shopping_arise.company.table_synthetic_action_rotation')
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
