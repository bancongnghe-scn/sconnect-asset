document.addEventListener('alpine:init', () => {
    Alpine.data('assetType', () => ({
        init() {
            $('.select2').select2()
            this.getListAssetType({
                page: 1,
                limit: 10
            })
            this.getListTypeGroup({})
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên loại tài sản',
            asset_type_group: 'Nhóm loại',
            maintenance_months: 'Thời gian bảo dưỡng(Tháng)',
            description: 'Ghi chú'
        },
        totalPages: null,
        currentPage: 1,
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
            asset_type_group_id: [],
            page: 1,
            limit: 10
        },
        assetType: {
            name: null,
            asset_type_group_id: null,
            maintenance_months: null,
            description: null,
        },
        titleAction: null,
        action: null,
        id: null,
        idModalConfirmDelete: "deleteAssetTypeGroup",
        listAssetTypeGroup: [],

        //methods
        async getListAssetType(filters) {
            this.loading = true
            const response = await window.apiGetAssetType(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total
                toast.success('Lấy danh sách loại tài sản thành công !')
            } else {
                toast.error('Lấy danh sách loại tài sản thất bại !')
            }
            this.loading = false
        },

        async getListTypeGroup(filters) {
            this.loading = true
            const response = await window.apiGetAssetTypeGroup(filters)
            if (response.success) {
                const data = response.data
                this.listAssetTypeGroup = data.data
            } else {
                toast.error('Lấy danh sách nhóm tài sản thất bại !')
            }
            this.loading = false
        },

        async editAssetType() {
            this.loading = true
            const response = await window.apiUpdateAssetType(this.assetType, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập loại tài sản thành công !')
            $('#modalAssetTypeUI').modal('hide');
            this.resetDataAssetType()
            await this.getListTypeGroup()
            this.loading = false
        },

        async removeAssetType() {
            this.loading = true
            const response = await window.apiRemoveAssetType(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa nhóm tài sản thành công !')

            this.getListType({
                page: 1,
                limit: this.filters.limit
            })

            this.loading = false
        },

        async createAssetType() {
            this.loading = true
            const response = await window.apiCreateAssetType(this.assetType)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo nhóm tài sản thành công !')
            $('#modalAssetTypeUI').modal('hide');
            this.resetDataAssetType()
            await this.getListAssetType()
            this.loading = false
        },

        async handShowModalAssetTypeUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.titleAction = 'Thêm mới'
            } else {
                this.titleAction = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAssetType(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.assetType.name = data.name
                this.assetType.description = data.description
                this.assetType.asset_type_group_id = data.asset_type_group_id
                this.assetType.maintenance_months = data.maintenance_months
            }

            $('#modalAssetTypeUI').modal('show');
        },

        handleAssetTypeUI() {
            if (this.action === 'create') {
                this.createAssetType()
            } else {
                this.editAssetType()
            }
        },

        changePage(page) {
            this.filters.page = page
            this.getListType(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListType(this.filters)
        },

        resetDataAssetType() {
            this.assetType.name = null
            this.assetType.asset_type_group_id = null
            this.assetType.maintenance_months = null
            this.assetType.description = null
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        searchAssetType() {
            this.filters.asset_type_group_id = $('select[name="asset_type_group"]').val()
            this.getListType(this.filters)
        }
    }));
});
