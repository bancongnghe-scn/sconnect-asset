
document.addEventListener('alpine:init', () => {
    Alpine.data('typeGroup', () => ({
        init() {
            this.getListTypeGroup({
                page: 1,
                limit: 10
            })
        },

        //dataTable
        dataTable: [],
        columns: {
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
        showChecked: false,

        //data
        filters: {
            name: null,
            page: 1,
            limit: 10
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
        searchTypeGroup() {
            this.filters.page = 1
            this.getListTypeGroup(this.filters)
        },

        async getListTypeGroup(filters) {
            this.loading = true
            const response = await this.apiGetAssetTypeGroup(filters)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            const data = response.data
            this.dataTable = data.data.data
            this.totalPages = data.data.last_page
            this.currentPage = data.data.current_page
            this.total = data.data.total
            toast.success('Lấy danh sách nhóm tài sản thành công !')
            this.loading = false
        },

        async apiGetAssetTypeGroup(filters) {
            try {
                const response = await axios.get("/api/asset-type-group", {
                    params: filters
                })

                const data = response.data;
                if (!data.success) {
                    return {
                        success: false,
                        message: data.message
                    }
                }

                return {
                    success: true,
                    data: data
                }
            } catch (error) {
                return {
                    success: false,
                    message: error?.response?.data?.message || error?.message
                }
            }
        },

        async editTypeGroup() {
            this.loading = true
            try {
                const param = {
                    name: this.createOrUpdateAssetTypeGroup.name,
                    description: this.createOrUpdateAssetTypeGroup.description,
                }
                const response = await axios.put("/api/asset-type-group/"+this.id, param)
                const data = response.data;
                if (!data.success) {
                    toast.error(data.message)
                    return
                }
                toast.success('Cập nhập nhóm tài sản thành công !')
                $('#modalCreateTypeGroup').modal('hide');
                this.resetDataCreateOrUpdateAssetTypeGroup()
                await this.getListTypeGroup(this.filters)
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
                const response = await axios.delete("/api/asset-type-group/"+this.id)
                const data = response.data;
                if (!data.success) {
                    toast.error(data.message)
                    return
                }
                $("#"+this.idModalConfirmDelete).modal('hide')
                await this.getListTypeGroup(this.filters)
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
                const response = await axios.post("/api/asset-type-group", this.createOrUpdateAssetTypeGroup)
                const data = response.data;
                if (!data.success) {
                    toast.error(data.message)
                    return
                }
                toast.success('Tạo nhóm tài sản thành công !')
                $('#modalCreateTypeGroup').modal('hide');
                window.location.reload();
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
            this.getListTypeGroup(this.filters)
        },

        changLimit() {
            this.filters.limit = this.limit
            this.getListTypeGroup(this.filters)
        },

        resetDataCreateOrUpdateAssetTypeGroup() {
            this.createOrUpdateAssetTypeGroup.name = null
            this.createOrUpdateAssetTypeGroup.description = null
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        }
    }));
})

