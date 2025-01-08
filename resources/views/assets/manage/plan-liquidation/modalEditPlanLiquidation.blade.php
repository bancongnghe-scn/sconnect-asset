<div class="modal fade" id="idModalEditPlanLiquidation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" x-init="$store.globalData.dataAssetDraftForCreatePlanLiquidation">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title tw-text-green-600" x-show="data.id" x-text="`${data.code} - ${data.name}`"></h4>
                <h4 class="modal-title tw-text-green-600" x-show="!data.id">Tạo mới kế hoạch</h4>
                <div class="ml-3 d-flex justify-content-center">
                    <span x-text="listStatusPlanLiquidation[data.status]" 
                        class="pl-2 pr-2 border-none rounded" 
                        :class="{
                            'tw-text-slate-500 tw-bg-slate-100':  listStatusPlanLiquidation[data.status] === 'Mới tạo',
                            'tw-text-yellow-500 tw-bg-yellow-100':  listStatusPlanLiquidation[data.status] === 'Chờ duyệt',
                            'tw-text-green-500 tw-bg-green-100':    listStatusPlanLiquidation[data.status] === 'Đã duyệt',
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
                                        <span>
                                            <label class="tw-font-bold">Mã kế hoạch</label>
                                        </span>
                                        <span class="text-danger">*</span>
                                        <input type="text" class="form-control" x-model="data.code" placeholder="Nhập mã kế hoạch">
                                    </div>
                                    <div class="col-3 mb-3">
                                        <span>
                                            <label class="tw-font-bold">Tên kế hoạch</label>
                                        </span>
                                        <span class="text-danger">*</span>
                                        <input type="text" class="form-control" x-model="data.name" placeholder="Nhập tên kế hoạch">
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
                                        <textarea name="" id="" class="form-control" x-model="data.note" placeholder="Nhập"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 active-link tw-w-fit">Danh sách tài sản thanh lý</div>
                                <table class="mb-3 table table-bordered table-hover dataPlanLiquidation dtr-inline"
                                    aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <template x-for="(columnName, key) in dataTheadListAssetLiqui">
                                            <th rowspan="1" colspan="1" x-text="columnName"></th>
                                        </template>
                                        <th rowspan="1" colspan="1" class="col-2 text-center">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="(dataAsset, index) in (data.id ? dataTbodyListAssetLiqui : $store.globalData.dataAssetDraftForCreatePlanLiquidation)" x-data="{line: 1}">
                                        <tr>
                                            <td>
                                                <span x-text="data.id ? dataAsset.asset.code : dataAsset.code"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.id ? dataAsset.asset.name : dataAsset.name"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.id ? dataAsset.asset.reason : dataAsset.reason"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.id ? dataAsset.price : dataAsset.price_liquidation"></span>
                                            </td>
                                            <td>
                                                <span x-text="data.id ? listStatusAssetOfPlan[dataAsset.status] : dataAsset.status" class="pl-2 pr-2 border rounded" 
                                                :class="{
                                                    'tw-text-gray-500 tw-bg-gray-100':      data.id ? listStatusAssetOfPlan[dataAsset.status] === 'Chưa duyệt'  : dataAsset.status,
                                                    'tw-text-green-500 tw-bg-green-100':    data.id ? listStatusAssetOfPlan[dataAsset.status] === 'Đã duyệt'    : dataAsset.status,
                                                    'tw-text-red-500 tw-bg-red-100':        data.id ? listStatusAssetOfPlan[dataAsset.status] === 'Từ chối'     : dataAsset.status,
                                                }"></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="border-0 bg-body" x-show="showAction.delete ?? true" @click="$dispatch('delete', { id: dataAsset.id })">
                                                    <i class="fa-solid fa-xmark tw-text-red-600"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>

                                <button class="mb-3 tw-w-fit border-0 tw-text-green-600 tw-bg-transparent" x-show="showAction.get ?? true" @click="$dispatch('get')">+ Thêm</button>
                                
                                <div>
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
                <button @click="data.id ? updatePlan(data.id ) : createPlan()" type="button" class="btn tw-bg-blue-500">Lưu</button>
                <button type="button" class="btn btn-sc" data-bs-dismiss="modal" x-show="data.id" @click="sendForApproval(data.id)">Gửi duyệt</button>
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

