@extends('layouts.app',[
    'title' => 'Nhóm tài sản'
])

@section('content')
    <div x-data="typeGroup">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" @click="handleShowModalCreateOrUpdate('create')">
                Thêm mới
            </button>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-row align-items-end tw-gap-x-4">
                        <div class="form-group col-4">
                            <label class="tw-font-bold">Tên nhóm tài sản</label>
                            <input type="text" class="form-control" x-model="filters.name">
                        </div>
                        <div class="">
                            <button @click="getListTypeGroup" type="button" class="btn btn-block btn-primary">Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            @edit="handleShowModalCreateOrUpdate('update', $event.detail.id)"
            @remove="confirmRemove($event.detail.id)"
            @page-change.window="changePage($event.detail.page)"
            @change-limit.window="getListTypeGroup"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div
            @save-type-group="handleCreateOrUpdate">
            @include('assets.asset_type_groups.modalCreateTypeGroup')
        </div>

        <div
            x-data="{
                modalId: idModalConfirmDelete,
                contentBody: 'Bạn có chắc chắn muốn xóa nhóm tài sản này không ?'
            }"
            @ok="removeTypeGroup"
        >
            @include('common.modal-confirm')
        </div>
    </div>
@endsection

@section('js')
    <script>
        function typeGroup() {
            return {
                //created
                init() {
                    $('.select2').select2()
                    this.getListTypeGroup()
                },

                //dataTable
                dataTable: [],
                columns: {
                    id: 'ID',
                    name: 'Tên loại',
                    description: 'Mô tả'
                },
                totalPages: null,
                currentPage: null,
                total: null,
                limit: 10,
                showAction: {
                    view: false,
                    edit: true,
                    remove: true
                },

                //data
                filters: {
                    name: null,
                    page: null,
                    limit: null
                },
                createOrUpdateAssetTypeGroup: {
                    name: null,
                    description: null,
                },
                titleAction: null,
                action: null,
                id: null,
                idModalConfirmDelete: "deleteAssetTypeGroup",

                //methods
                async getListTypeGroup() {
                    this.loading = true
                    this.filters.page = this.currentPage
                    this.filters.limit = this.limit
                    const response = await this.apiGetAssetTypeGroup(this.filters)
                    if (response.success) {
                        const data = response.data
                        this.dataTable = data.data.data
                        this.totalPages = data.data.last_page
                        this.currentPage = data.data.current_page
                        this.total = data.data.total
                    }
                },

                async apiGetAssetTypeGroup(filters) {
                    this.loading = true
                    try {
                        const response = await axios.get("{{ route('asset.type_group.list') }}", {
                            params: filters
                        })

                        const data = response.data;
                        if (!data.success) {
                            toast.error(data.message)
                            return {
                                success: false,
                            }
                        }

                        toast.success('Lấy danh sách nhóm tài sản thành công !')
                        return {
                            success: true,
                            data: data
                        }
                    } catch (error) {
                        this.loading = false
                        toast.error(error?.response?.data?.message || error?.message)
                        return {
                            success: false,
                        }
                    } finally {
                        this.loading = false
                    }
                },

                async editTypeGroup() {
                    this.loading = true
                    try {
                        const param = {
                            id: this.id,
                            name: this.createOrUpdateAssetTypeGroup.name,
                            description: this.createOrUpdateAssetTypeGroup.description,
                        }
                        const response = await axios.post("{{ route('asset.type_group.update') }}", param)
                        const data = response.data;
                        if (!data.success) {
                            toast.error(data.message)
                            return
                        }
                        toast.success('Cập nhập nhóm tài sản thành công !')
                        $('#modalCreateTypeGroup').modal('hide');
                        this.resetDataCreateOrUpdateAssetTypeGroup()
                        await this.getListTypeGroup()
                    } catch (error) {
                        toast.error(error?.response?.data?.message || error?.message)
                        $('#modalCreateTypeGroup').modal('hide');
                    } finally {
                        this.loading = false
                    }
                },

                async removeTypeGroup() {
                    this.loading = true
                    try {
                        const response = await axios.post("{{ route('asset.type_group.delete') }}", {id: this.id})
                        const data = response.data;
                        if (!data.success) {
                            toast.error(data.message)
                            return
                        }
                        $("#"+this.idModalConfirmDelete).modal('hide')
                        await this.getListTypeGroup()
                        toast.success('Xóa nhóm tài sản thành công !')
                    } catch (error) {
                        toast.error(error?.response?.data?.message || error?.message)
                    } finally {
                        this.loading = false
                    }
                },

                async createTypeGroup() {
                    this.loading = true
                    try {
                        const response = await axios.post("{{ route('asset.type_group.create') }}", this.createOrUpdateAssetTypeGroup)
                        const data = response.data;
                        if (!data.success) {
                            toast.error(data.message)
                            return
                        }
                        toast.success('Tạo nhóm tài sản thành công !')
                        $('#modalCreateTypeGroup').modal('hide');
                        this.resetDataCreateOrUpdateAssetTypeGroup()
                        await this.getListTypeGroup()
                    } catch (error) {
                        $('#modalCreateTypeGroup').modal('hide');
                        toast.error(error?.response?.data?.message || error?.message)
                    } finally {
                        this.loading = false
                    }
                },

                async handleShowModalCreateOrUpdate(action, id = null) {
                    this.action = action
                    if (action === 'create') {
                        this.titleAction = 'Thêm mới'
                    } else {
                        this.titleAction = 'Cập nhật'
                        this.id = id
                        const filters = {
                            id: id,
                            page: 1,
                            limit: 1
                        }
                        const response = await this.apiGetAssetTypeGroup(filters)
                        if (response.success) {
                            const data = response.data.data.data
                            this.createOrUpdateAssetTypeGroup.name = data[0].name
                            this.createOrUpdateAssetTypeGroup.description = data[0].description
                        }
                    }
                    $('#modalCreateTypeGroup').modal('show');
                },

                handleCreateOrUpdate() {
                    if (this.action === 'create') {
                        this.createTypeGroup()
                    } else {
                        this.editTypeGroup()
                    }
                },

                changePage(page) {
                    this.filters.page = page
                    this.getListTypeGroup()
                },

                resetDataCreateOrUpdateAssetTypeGroup() {
                    this.createOrUpdateAssetTypeGroup.name = null
                    this.createOrUpdateAssetTypeGroup.description = null
                },

                confirmRemove(id) {
                    $("#"+this.idModalConfirmDelete).modal('show');
                    this.id = id
                }
            }
        }

    </script>
@endsection
