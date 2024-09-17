@extends('layouts.app',[
    'title' => 'Nhóm tài sản'
])

@section('content')
    <div x-data="typeGroup">
        <div class="tw-mb-3 d-flex tw-justify-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateTypeGroup">
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
                            <button @click="getListTypeGroup" type="button" class="btn btn-block btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            @edit="editTypeGroup($event.detail.id)"
            @remove="removeTypeGroup($event.detail.id)"
            @page-change.window="changePage($event.detail.page)"
            @change-limit.window="getListTypeGroup"
        >
            @include('common.table')
        </div>

        {{-- modal--}}
        <div
        @save-type-group="createTypeGroup"
        >
            @include('assets.asset_type_groups.modalCreateTypeGroup')
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
                createAssetTypeGroup: {
                    name: null,
                    description: null,
                },

                //methods
                async getListTypeGroup() {
                    this.loading = true
                    this.filters.page = this.currentPage
                    this.filters.limit = this.limit
                    await axios.get("{{ route('asset.type_group.list') }}", {
                        params: this.filters
                    })
                        .then(response => {
                            const data = response.data;
                            if (!data.success) {
                                this.loading = false
                                toast.error(data.message)
                                return
                            }
                            this.dataTable = data.data.data
                            this.totalPages = data.data.last_page
                            this.currentPage = data.data.current_page
                            this.total = data.data.total
                            this.loading = false
                            toast.success('Lấy danh sách nhóm tài sản thành công !')
                        })
                        .catch(error => {
                            this.loading = false
                            toast.error(error?.response?.data?.message || error?.message)
                        });
                },

                editTypeGroup(id) {
                    console.log(id)
                },

                async removeTypeGroup(id) {
                    this.loading = true
                    await axios.post("{{ route('asset.type_group.delete') }}", {id: id})
                        .then(response => {
                            const data = response.data;
                            if (!data.success) {
                                this.loading = false
                                toast.error(data.message)
                                return
                            }
                            this.getListTypeGroup()
                            toast.success('Xóa nhóm tài sản thành công !')
                        })
                        .catch(error => {
                            this.loading = false
                            toast.error(error?.response?.data?.message || error?.message)
                        });
                },

                async createTypeGroup() {
                    this.loading = true
                    await axios.post("{{ route('asset.type_group.create') }}", this.createAssetTypeGroup)
                        .then(response => {
                            const data = response.data;
                            if (!data.success) {
                                this.loading = false
                                toast.error(data.message)
                                return
                            }
                            $('#modalCreateTypeGroup').modal('hide');
                            toast.success('Tạo nhóm tài sản thành công !')
                            this.getListTypeGroup()
                        })
                        .catch(error => {
                            this.loading = false
                            toast.error(error?.response?.data?.message || error?.message)
                        });
                },

                changePage(page) {
                    this.filters.page = page
                    this.getListTypeGroup()
                }
            }
        }

    </script>
@endsection
