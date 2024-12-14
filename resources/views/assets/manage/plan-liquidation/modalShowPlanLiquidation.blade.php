<div class="modal fade" id="idModalShowPlanLiquidation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" x-data="tableModalShowPlanLiquidation">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title tw-text-green-600" x-show="data.id" x-text="data.code"></h4>
                <h4 class="modal-title tw-text-green-600" x-show="!data.id">Tạo mới kế hoạch</h4>
                <div class="ml-3 d-flex justify-content-center">
                    <span x-text="listStatusPlanLiquidation[data.status]" 
                        class="pl-2 pr-2 border-none rounded" 
                        :class="{
                            'tw-text-blue-400 tw-bg-blue-100':  listStatusPlanLiquidation[data.status] === 'Mới tạo',
                            'tw-text-yellow-500 tw-bg-yellow-100':  listStatusPlanLiquidation[data.status] === 'Chờ duyệt',
                            'tw-text-green-400 tw-bg-green-200':    listStatusPlanLiquidation[data.status] === 'Đã duyệt',
                            'tw-text-red-500 tw-bg-red-100':    listStatusPlanLiquidation[data.status] === 'Từ chối',
                        }"
                    ></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="row">
                        <div :class="data.id ? 'col-9' : 'col-12'">
                            <div class="container mb-3">
                                <div class="mb-3 active-link tw-w-fit">Thông tin chung</div>
                                <div class="row mb-3">
                                    <div class="col-2 mb-3">
                                        <label class="tw-font-bold">Mã kế hoạch</label>
                                        <input type="text" class="form-control" disabled x-model="data.code" placeholder="Nhập mã kế hoạch">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="tw-font-bold">Tên kế hoạch</label>
                                        <input type="text" class="form-control" disabled x-model="data.name" placeholder="Nhập tên kế hoạch">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <label class="tw-font-bold">Được tạo bởi</label>
                                        <input type="hidden" id="authUserName" value="{{ Auth::user()?->name }}">
                                        <input disabled type="text" class="form-control" :value="data.user ? data.user.name : $('#authUserName').val()">
                                    </div>
                                    <div class="col-4 mb-3">
                                        <label class="tw-font-bold">Ngày tạo</label>
                                        <div class="input-group">
                                            <input type="text" disabled class="form-control" autocomplete="off" x-model="data.created_at" x-effect="$el.value = formatDate(data.created_at)">
                                            <span class="input-group-text">
                                                <i class="fa-regular fa-calendar-days"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="tw-font-bold">Ghi chú</label>
                                        <textarea name="" id="" class="form-control" disabled x-model="data.note" placeholder="Nhập"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 row" x-show="data.id">
                                    <div class="col-4 active-link">
                                        Danh sách tài sản thanh lý
                                    </div>
                                    <div class="col-2"></div>
                                    <div class="col-6" x-show="dataTbodyListAssetLiqui.some(data => listStatusAssetOfPlan[data.status] === 'Chưa duyệt')">
                                        <div class="row">
                                            <div class="col-6 text-end">
                                                <button type="button" style="background-color: #dff7e9!important;" class="btn tw-text-black" @click="handleUpdateAssetOfPlanMulti('approve')" :disabled="window.checkDisableSelectRowOfModalShowPlan">
                                                    <i class="fa-solid fa-check" style="color: #28c76f;">&#xF117;</i>
                                                    <span>
                                                        Duyệt
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="col-6 text-end">
                                                <button type="button" style="background-color: #fce5e6 !important;" class="btn tw-text-black" @click="handleUpdateAssetOfPlanMulti('cancel')" :disabled="window.checkDisableSelectRowOfModalShowPlan">
                                                    <i class="fa-solid fa-xmark" style="color: #ea5455;">&#xF117;</i>
                                                    <span>
                                                        Từ chối
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="mb-3 table table-bordered table-hover dataPlanLiquidation dtr-inline" x-show="data.id"
                                    aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" id="selectedAllAssetOfPlanLiqui" @click="selectedAllAssetOfPlanLiqui">
                                        </th>
                                        <template x-for="(columnName, key) in dataTheadListAssetLiqui">
                                            <th rowspan="1" colspan="1" x-text="columnName"></th>
                                        </template>
                                        <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(data,index) in dataTbodyListAssetLiqui" x-data="{line: 1}">
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="checkbox"
                                                    x-show="listStatusAssetOfPlan[data.status] === 'Chưa duyệt'"
                                                    x-model="selectedRowOfModalShowPlan[data.id]"
                                                    x-bind:checked="selectedRowOfModalShowPlan[data.id]">
                                            </td>
                                            <td>
                                                <span x-text="data.asset.code"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.asset.name"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.asset.reason"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.price"></span>
                                            </td>
                                            <td>
                                                <span x-text="listStatusAssetOfPlan[data.status]" class="pl-2 pr-2 border rounded" 
                                                :class="{
                                                    'tw-text-gray-500 tw-bg-gray-100':  listStatusAssetOfPlan[data.status] === 'Chưa duyệt',
                                                    'tw-text-green-500 tw-bg-green-100':    listStatusAssetOfPlan[data.status] === 'Đã duyệt',
                                                    'tw-text-red-500 tw-bg-red-100':    listStatusAssetOfPlan[data.status] === 'Từ chối',
                                                }"></span>
                                            </td>
                                            <td class="text-center align-middle" x-show="listStatusAssetOfPlan[data.status] === 'Chưa duyệt'">
                                                <button class="border-0 bg-body" x-show="showAction.approve ?? true" @click="$dispatch('approve', { id: data.id })">
                                                    <i class="fa-solid fa-check" style="color: #28c76f;;"></i>
                                                </button>
                                                <button class="border-0 bg-body" x-show="showAction.cancel ?? true" @click="$dispatch('cancel', { id: data.id })">
                                                    <i class="fa-solid fa-xmark" style="color: #cd1326;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>

                                
                                <div
                                    {{-- @delete="handleDeleteOfMultiModalCancelUI($event.detail.id)" --}}
                                >
                                    @include('assets.manage.plan-liquidation.modalSelectAsset')
                                </div>
            
                            </div>
                        </div>
                        <div class="col-3" x-show="data.id">
                            <div class="container p-2" style="background: #fff;border-radius: 7px">
                                <span id="tab-container" class="d-inline-block">
                                    <ul id="ul-tab" class="nav nav-tabs" style="background-color: #ffffff; border: 0; white-space: nowrap; flex-flow: nowrap;border-bottom:1px solid #e9ecef" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="#comments" class="tab-show active" tab-value="budget" data-bs-toggle="tab" aria-selected="true" role="tab">Bình luận (<span class="totalComment">1</span>)</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#histories" class="tab-show" tab-value="done" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Lịch sử (<span class="totalHistory">4</span>)</a>
                                        </li>
                                    </ul>
                                </span>
                                <div class="tab-content mr-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @can('liquidation_asset.hr_manager_approval')
                <button class="btn bg-body" 
                    x-show="listStatusPlanLiquidation[data.status] === 'Chờ duyệt'"
                    style="border: 1px solid rgba(55, 146, 55, 1);border-radius: 8px;"
                    @click="confirmPlan('Đã duyệt')"    
                >
                    <i class="fa-solid fa-check" style="color: #28c76f;;"></i>
                    Hoàn thành
                </button>
                <button class="btn bg-body" 
                    x-show="listStatusPlanLiquidation[data.status] === 'Chờ duyệt'"
                    style="border: 1px solid rgba(55, 146, 55, 1);border-radius: 8px;"
                    @click="confirmPlan('Từ chối')"    
                >
                    <i class="fa-solid fa-xmark" style="color: #cd1326;"></i>
                    Từ chối
                </button>
                @endcan
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
<style>
    .air-datepicker {
        z-index: 3000; /* Đảm bảo giá trị này lớn hơn z-index của modal Bootstrap (thường là 1050) */
    }
</style>
<script>
    function tableModalShowPlanLiquidation() {

        return {
            checkedAll: false,

            selectedAllAssetOfPlanLiqui() {
                const status_new = Number(Object.keys(this.listStatusAssetOfPlan).find(
                    k => this.listStatusAssetOfPlan[k] === 'Chưa duyệt'
                ))
                
                this.checkedAll = !this.checkedAll
                this.dataTbodyListAssetLiqui
                    .filter((item) => item.status === status_new)
                    .forEach(
                    (item) => {
                        this.selectedRowOfModalShowPlan[item.id] = this.checkedAll
                    }
                )
            }
        }
    }
</script>
