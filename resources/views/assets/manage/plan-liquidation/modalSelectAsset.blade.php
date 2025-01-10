<div class="modal fade modal-2" x-data="tableSelectAssetToPlan" id="idModalSelectAsset" tabindex="-1" aria-labelledby="idModalSelectAsset" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content tw-w-2/3 tw-overflow-auto tw-m-auto tw-mt-28">
            <div class="modal-header">
                <h4 class="modal-title" x-text="'Thêm tài sản'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body tw-overflow-y-auto tw-max-h-80">
                <div class="container mb-3">
                    <div class="row mb-3">
                        <div class="col-5 fw-bold" style="text-decoration: underline;color: #28a745;">Danh sách tài sản đề nghị thanh lý</div>
                        <div class="col-7">
                            <input type="text" class="form-control" x-model="filterMore.name_code" placeholder="Tên/mã tài sản" @keydown.enter="modalSelectAsset(filterMore)">
                        </div>
                    </div>
                    <table id="" class="table table-bordered table-hover dataTable dtr-inline"
                            aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="selectedAllAsset" @click="selectedAllAsset">
                            </th>
                            <template x-for="(columnName, key) in dataTheadSelectAsset">
                                <th rowspan="1" colspan="1" x-text="columnName"></th>
                            </template>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(data,index) in dataTbodySelectAsset" x-data="{line: 1}">
                            <tr>
                                <td class="text-center align-middle">
                                    <input type="checkbox" x-model="selectedRowAssetToPlan[data.id]" x-bind:checked="selectedRowAssetToPlan[data.id]">
                                </td>
                                <template x-for="(columnName, key) in dataTheadSelectAsset">
                                    <td>
                                        <template x-if="key !== 'validity' && key !== 'status'">
                                            <span x-text="data[key]"></span>
                                        </template>
                                        <template x-if="key === 'validity'">
                                            <div class="text-white d-flex justify-content-center">
                                                <span class="tw-px-4 tw-py-1 tw-rounded-full"
                                                        :class="data[key] ? 'tw-bg-[#54B435]' : 'tw-bg-slate-300'"
                                                        x-text="data[key] ? 'On' : 'Off'">
                                                </span>
                                            </div>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    @include('assets.manage.plan-liquidation.paginationMore')
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="addAssetToPlanLiquidation()" type="button" class="btn btn-sc">Xác nhận</button>
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
    function tableSelectAssetToPlan() {
        return {
            checkedAll: false,

            selectedAllAsset() {
                
                this.checkedAll = !this.checkedAll
                this.dataTbodySelectAsset.forEach(
                    (item) => {
                        this.selectedRowAssetToPlan[item.id] = this.checkedAll
                    }
                )
            }
        }
    }
</script>
