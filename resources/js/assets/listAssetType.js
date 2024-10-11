document.addEventListener('alpine:init', () => {
    Alpine.data('assetType', () => ({
        init() {
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
            measure: 'Đơn vị tính',
            description: 'Ghi chú'
        },
        showAction: {
            view: false,
            edit: true,
            remove: true
        },
        selectedRow: [],
        showChecked: true,
        //pagination
        totalPages: null,
        currentPage: 1,
        from: null,
        to: null,
        total: null,
        limit: 10,

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
            measure: null,
        },
        listMeasure: {
            1: 'Chiếc',
            2: 'Cái',
            3: 'Bộ',
            4: 'Bình',
            5: 'Cuộn',
            6: 'Hộp',
            7: 'Túi',
            8: 'Lọ',
            9: 'Thùng',
            10: 'Đôi',
        },
        titleAction: null,
        action: null,
        id: null,
        idModalConfirmDelete: "deleteAssetTypeGroup",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
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
                this.from = data.data.from
                this.to = data.data.to
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
                toast.error('Lấy danh sách loại tài sản thất bại !')
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
            await this.getListAssetType(this.filters)
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
            toast.success('Xóa loại tài sản thành công !')

            this.getListAssetType(this.filters)

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
            toast.success('Tạo loại tài sản thành công !')
            $('#modalAssetTypeUI').modal('hide');
            this.loading = false
            window.location.reload();
        },

        async deleteMultiple() {
            this.loading = true
            const ids = Object.keys(this.selectedRow)
            const response = await window.apiDeleteMultipleByIds(ids)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('hide');

            toast.success('Xóa danh sách loại tài sản thành công !')

            this.getListAssetType(this.filters)

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
            this.getListAssetType(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListAssetType(this.filters)
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

        confirmDeleteMultiple() {
            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
        },

        searchAssetType() {
            this.filters.asset_type_group_id = $('select[name="asset_type_group"]').val()
            this.getListAssetType(this.filters)
        }
    }));
});
