
document.addEventListener('alpine:init', () => {
    Alpine.data('typeGroup', () => ({
        init() {
            this.list({page: 1, limit: 10})
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên loại',
            description: 'Mô tả'
        },
        showAction: {
            view: false,
            edit: true,
            remove: true
        },
        selectedRow: [],

        //pagination
        totalPages: null,
        currentPage: null,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        //data
        filters: {
            name: null,
            page: 1,
            limit: 10
        },
        data: {
            name: null,
            description: null,
        },
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: 'idModalConfirmDelete',
        idModalConfirmDeleteMultiple: 'idModalConfirmDeleteMultiple',
        idModalUI: 'modalUI',

        //methods
        async list(filters) {
            this.loading = true
            const response = await window.apiGetAssetTypeGroup(filters)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            const data = response.data
            this.dataTable = data.data.data
            this.totalPages = data.data.last_page
            this.currentPage = data.data.current_page
            this.from = data.data.from ?? 0
            this.to = data.data.to ?? 0
            this.total = data.data.total ?? 0
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAssetTypeGroup(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập nhóm tài sản thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false

        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAssetTypeGroup(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa nhóm tài sản thành công !')
            this.loading = false
        },

        async removeMultiple() {
            this.loading = true
            const response = await window.apiDeleteAssetTypeGroupMultiple(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách nhóm tài sản thành công !')
            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateAssetTypeGroup(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo nhóm tài sản thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
            this.loading = false
        },

        async handleShowModal(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAssetTypeGroup(id)
                if (!response.success) {
                    this.loading = false
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.data.name = data.name
                this.data.description = data.description
            }
            this.loading = false
            $('#'+this.idModalUI).modal('show');
        },

        resetData() {
            this.data = {
                name: null,
                description: null,
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name: null,
                page: 1,
                limit: 10
            }
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn nhóm tài sản cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },
    }));
})

