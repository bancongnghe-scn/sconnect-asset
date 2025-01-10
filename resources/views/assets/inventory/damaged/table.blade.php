<div class="col-12" x-data="tableDamaged">
    <div id="" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table table-bordered table-hover dataTable dtr-inline"
                        aria-describedby="example2_info">
                    <thead>
                    <tr :class="'position-sticky tw-top-0'">
                        <th class="text-center">
                            <input type="checkbox" id="selectedAllAssetDamaged" @click="selectedAllAssetDamaged" @change="countAssetDamaged()">
                        </th>
                        <template x-for="(columnName, key) in columns">
                            <th rowspan="1" colspan="1" x-text="columnName"></th>
                        </template>
                        <th rowspan="1" colspan="1" class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(data,index) in dataTable" x-data="{line: 1}">
                        <tr>
                            <td class="text-center align-middle">
                                <input type="checkbox" 
                                    x-model="selectedRow[data.id]" 
                                    x-bind:checked="selectedRow[data.id]"
                                    @change="countAssetDamaged()">
                            </td>
                            <template x-for="(columnName, key) in columns">
                                <td :class="'align-content-center'">
                                    <template x-if="key !== 'validity' && key !== 'status' && key !== 'date' && key !== 'user_name' ">
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
                                    
                                    <template x-if="key === 'date'">
                                        <span x-text="formatDate(data.date)"></span>
                                    </template>
                                    <template x-if="key === 'user_name'">
                                        <div class="d-flex">
                                            <img x-show="data.user_name" x-bind:src="data.user_avatar 
                                                    ? (data.user_avatar.includes('/uploads/') 
                                                        ? 'https://office.sconnect.com.vn' + data.user_avatar 
                                                        : data.user_avatar) 
                                                    : 'https://office.sconnect.com.vn/images/avatar-default.png'" 
                                                    alt="" 
                                                    style="width: 55px; height: 55px; object-fit: cover; border-radius: 100px;">
                                            <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; margin-left: 10px;">                                   
                                                <span x-text="data.user_name ?? ''" style="font-weight: 600; font-size: 16px;"></span>
                                                <span x-text="data.user_code ? 'Mã nhân sự:' + data.user_code : ''" style="color: #706f6f;"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="key === 'status'">
                                        @include('common.table-status-asset')
                                    </template>
                                </td>
                            </template>
                            <td class="text-center align-middle">
                                <button class="border-0 position-relative" style="background-color: unset;" x-show="showAction.repaid ?? true" @click="$dispatch('repaid', { id: data.id })">
                                    <img src="/images/icon-setting.jpg" style="scale: 0.85">
                                    <span class="tooltip-text">Sửa chữa</span>
                                </button>
                                <button class="border-0 position-relative" style="background-color: unset;" x-show="showAction.liquidation ?? true" @click="$dispatch('liquidation', { id: data.id })">
                                    <img src="/images/icon-liquidation.jpg" style="scale: 0.85">
                                    <span class="tooltip-text">Đề nghị thanh lý</span>
                                </button>
                                <button class="border-0 position-relative" style="background-color: unset;" x-show="showAction.cancel ?? true" @click="$dispatch('cancel', { id: data.id })">
                                    <img src="/images/icon-cancel.jpg" style="scale: 0.85">
                                    <span class="tooltip-text"> Hủy</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('common.pagination')
</div>

<script>
    function tableDamaged() {
        return {
            checkedAll: false,

            selectedAllAssetDamaged() {
                this.checkedAll = !this.checkedAll
                this.dataTable.forEach(
                    (item) => this.selectedRow[item.id] = this.checkedAll
                )
            }
        }
    }
</script>

