<div class="row" x-data="inventory_user">
    <div class="col-12">
        <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 table-responsive custom-scroll">
                    <table id="example2" class="table table-bordered dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th rowspan="1" colspan="1" class="text-center">Tên kế hoạch kiểm kê</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 17rem">Thời gian bắt đầu</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 17rem">Thời gian kết thúc</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 9rem">Trạng thái</th>
                            <th rowspan="1" colspan="1" class="text-center" style="width: 7rem">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(value,index) in dataTable">
                            <tr>
                                <td x-text="value.name" class="align-middle"></td>
                                <td x-text="formatDateVN(value.start_time)" class="text-center align-middle"></td>
                                <td x-text="formatDateVN(value.end_time)" class="text-center align-middle"></td>
                                <td class="align-middle">
                                    @include('component.status.status_plan_inventory', ['status' => 'value.status'])
                                </td>
                                <td class="text-center align-middle">
                                    <button class="border-0 bg-white" @click="handleShowModal('view', value.id)">
                                        <i class="bi bi-eye text-info"></i>
                                    </button>
                                    <template x-if="+value.status === STATUS_TAKING_INVENTORY">
                                        <button class="border-0 bg-white" @click="handleShowModal('upload', value.id)">
                                            <i class="bi bi-pencil-square color-sc"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            @change-page.windows.stop="changePage($event.detail.page)"
            @change-limit.window.stop="changeLimit($event.detail.limit)">
            @include('common.pagination')
        </div>
    </div>

    {{--modal--}}
    @include('assets.asset.user.inventory.modal_upload_file')
    @include('assets.asset.user.inventory.modal_list_file')

    <script>
        function inventory_user() {
            return {
                init() {
                    this.getListPlanInventory()
                },

                //dataTable
                dataTable: [],
                totalPages: null,
                currentPage: 1,
                total: 0,
                from: 0,
                to: 0,

                filters: {
                   page: 1,
                   limit: 10
                },
                file: [],
                id: null,

                async getListPlanInventory(){
                    this.loading = true
                    try {
                        const response = await window.apiGetListPlanInventoryUser(this.filters)
                        if (!response.success) {
                            toast.error(response.message)
                            return
                        }
                        const data = response.data
                        this.dataTable = data.data.data
                        this.totalPages = data.data.last_page
                        this.currentPage = data.data.current_page
                        this.total = data.data.total ?? 0
                        this.from = data.data.from ?? 0
                        this.to = data.data.to ?? 0
                    } catch (e) {
                        toast.error(e)
                    } finally {
                        this.loading = false
                    }
                },

                async handleShowModal(action, id) {
                    this.file = []
                    this.id = id
                    if (action === 'upload') {
                        $('#modalUploadFile').modal('show')
                        return
                    }

                    await this.getFileUploaded()
                    $('#modalListFile').modal('show')
                },

                handleFile() {
                    this.file = this.$refs.fileInput.files[0]
                },

                async uploadFileInventory() {
                    this.loading = true
                    try {
                        const response = await window.apiUploadFileInventory({
                            id: this.id,
                            file: this.file
                        })
                        if (!response.success) {
                            toast.error(response.message)
                            return
                        }

                        toast.success('Kết quả của bạn đã được hệ thống ghi nhân')
                        $('#modalUploadFile').modal('hide')
                    } catch (e) {
                        toast.error(e)
                    } finally {
                        this.loading = false
                    }
                },

                async getFileUploaded() {
                    this.loading = true
                    try {
                        const response = await window.apiGetFileUploaded(this.id)
                        if (!response.success) {
                            toast.error(response.message)
                            return
                        }
                        this.file = response.data.data
                    } catch (e) {
                        toast.error(e)
                    } finally {
                        this.loading = false
                    }
                }
            }
        }
    </script>
</div>
