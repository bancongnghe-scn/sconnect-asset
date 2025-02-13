<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kế hoạch mua sắm quý</h4>
                <div class="mb-3 d-flex gap-2 justify-content-end">
                    <template x-if="
                        (+data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER
                        || +data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED)
                        && ( new Date() > new Date(data.start_time) &&  new Date() < new Date(data.end_time))">
                        <button class="btn btn-primary" @click="sentRegister">Đăng ký</button>
                    </template>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-warning">Quay lại</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex tw-gap-x-3 h-100">
                    <div class="flex-grow-1 overflow-auto custom-scroll">
                        {{--Thông tin chung--}}
                        <div class="mb-3">
                            <div class="d-flex tw-gap-x-4 mb-3">
                                <div class="active-link tw-w-fit">Thông tin chung</div>
                                @include('component.status.status_shopping_plan_organization', ['status' => 'data.status'])
                            </div>
                            <div class="tw-grid tw-grid-cols-3 tw-gap-4">
                                <div>
                                    <label class="tw-font-bold">Tên</label>
                                    <div class="form-control" style="background-color: #E5E7EB" x-text="data.name"></div>
                                </div>

                                <div>
                                    <label class="tw-font-bold">Đơn vị</label>
                                    <div class="form-control" style="background-color: #E5E7EB" x-text="data.organization_name"></div>
                                </div>

                                <div>
                                    <label class="tw-font-bold">Thời gian đăng ký</label>
                                    <template x-if="data.start_time !== null">
                                        @include('common.datepicker.datepicker_range', [
                                             'placeholder' => 'Chọn thời gian đăng ký',
                                             'disabled' => true,
                                             'start' => 'data.start_time',
                                             'end' => 'data.end_time',
                                        ])
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Chi tiết--}}
                        <div class="mb-3">
                            <div class="mb-3 active-link tw-w-fit">Chi tiết</div>
                            <div>
                                <template x-for="(register, index) in registers" :key="index">
                                    <div class="p-4 tw-bg-[#E4F0E6] mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 d-flex align-items-center tw-gap-x-6 mr-5">
                                                <span class="form-control" style="flex: 1;" x-text="`Tháng ${register.month}`"></span>

                                                <div class="d-flex align-items-center" style="flex: 1;">
                                                    <span class="me-2 flex-shrink-0 tw-font-bold">Tổng số lượng</span>
                                                    <span class="form-control text-center" x-text="`${register.register.total}`"></span>
                                                </div>

                                                <div class="d-flex align-items-center" style="flex: 1;">
                                                    <span class="me-2 flex-shrink-0 tw-font-bold">Tổng giá trị</span>
                                                    <span class="form-control text-center"
                                                          x-text="`${window.formatCurrencyVND(register.register.price)}`"
                                                    ></span>
                                                </div>
                                            </div>

                                            <button class="btn" @click="handleShowTable(index)">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>

                                        <div class="card card-body mt-3" x-show="table_index.includes(index)">
                                            <div class="tw-max-w-full">
                                                <table id="example2" class="table table-bordered dataTable dtr-inline" aria-describedby="example2_info">
                                                    <thead>
                                                    <tr class="tw-text-nowrap">
                                                        <th rowspan="1" colspan="1">Loại tài sản</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-24">Đơn vị tính</th>
                                                        <th rowspan="1" colspan="1">Chức danh</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-32">Đơn giá</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-24">Số lượng</th>
                                                        <th rowspan="1" colspan="1" class="tw-w-48">Tổng</th>
                                                        <th rowspan="1" colspan="1">Mô tả</th>
                                                        <th rowspan="1" colspan="1"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <template x-for="(asset, key) in register.assets" :key="`asset_${asset.id || asset.id_fake}`">
                                                        <tr class="tw-text-nowrap"
                                                            x-data="{
                                                                    get price() {
                                                                        if (!asset.asset_type_id || !asset.job_id) {
                                                                            return 0
                                                                        }
                                                                        asset.price = +(asset.asset_type_id + asset.job_id + 1000)
                                                                        return +(asset.asset_type_id + asset.job_id + 1000)
                                                                    }
                                                            }"
                                                            x-init="$watch('asset.price', value => calculatePrice(index))"
                                                        >
                                                            <td>
                                                                @include('common.select_custom.extent.select_single', [
                                                                        'placeholder' => 'Chọn tài sản',
                                                                        'selected' => 'asset.asset_type_id',
                                                                        'options' => 'list_asset_type',
                                                                ])
                                                            </td>
                                                            <td class="align-middle" x-text="asset.asset_type_id ? list_asset_type.find((item) => +item.id === +asset.asset_type_id).measure : ''"></td>
                                                            <td>
                                                                @include('common.select_custom.extent.select_single', [
                                                                            'placeholder' => 'Chọn chức danh',
                                                                            'selected' => 'asset.job_id',
                                                                            'options' => 'list_job',
                                                                ])
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(price)"></td>
                                                            <td>
                                                                <input class="form-control" type="number" min="1"
                                                                       x-model="asset.quantity_registered"
                                                                       @change="validateQuantityRegistered(asset.quantity_registered)"
                                                                       @input="
                                                                               asset.quantity_approved = asset.quantity_registered
                                                                               calculateRegister(index)
                                                                           "
                                                                >
                                                            </td>
                                                            <td class="align-middle" x-text="window.formatCurrencyVND(asset.quantity_registered * asset.price)"></td>
                                                            <td>
                                                                <input class="form-control" x-model="asset.description" type="text">
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button class="border-0 bg-white" @click="deleteRow(index, key)">
                                                                    <i class="fa-regular fa-trash-can" style="color: #cd1326;"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-sc mt-3 tw-w-fit" @click="addRow(index)">Thêm hàng</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="col-2 border border-right-0 border-top-0 border-bottom-0">
                        @include('assets.shopping_plan_organization.history_comment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

