document.addEventListener('alpine:init', () => {
    Alpine.data('assetType', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.getListTypeGroup({})
            window.initSelect2Modal(this.idModalUI);
            this.onChangeSelect2()
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

        //pagination
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        //data
        filters: {
            name: null,
            asset_type_group_id: [],
            page: 1,
            limit: 10
        },
        data: {
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
        listAssetTypeGroup: [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "modalUI",

        //methods
        async list(filters) {
            this.loading = true
            const response = await window.apiGetAssetType(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
                toast.success('Lấy danh sách loại tài sản thành công !')
            } else {
                toast.error('Lấy danh sách loại tài sản thất bại !')
            }
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAssetType(this.data, this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false
                return
            }
            toast.success('Cập nhập loại tài sản thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAssetType(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa loại tài sản thành công !')
            this.list(this.filters)
            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateAssetType(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo loại tài sản thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
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

        async deleteMultiple() {
            this.loading = true
            const response = await window.apiDeleteMultipleByIds(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách loại tài sản thành công !')
            this.loading = false
        },

        async handShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAssetType(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data
            }
            this.loading = false
            $('#'+this.idModalUI).modal('show');
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        resetData() {
            this.data = {
                name: null,
                asset_type_group_id: null,
                maintenance_months: null,
                description: null,
                measure: null,
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name: null,
                asset_type_group_id: [],
                page: 1,
                limit: 10
            }
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmDeleteMultiple() {
            const ids = Object.keys(this.selectedRow)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn loại tài sản cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterAssetTypeGroup') {
                    this.filters.asset_type_group_id = value
                } else if (event.target.id === 'filterStatusContract') {
                    this.filters.status = value
                } else if (event.target.id === 'selectAssetTypeGroup') {
                    this.data.asset_type_group_id = value
                } else if (event.target.id === 'selectMeasure') {
                    this.data.measure = value
                }
            });
        },
    }));
});
