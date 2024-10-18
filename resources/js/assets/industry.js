document.addEventListener('alpine:init', () => {
    Alpine.data('industry', () => ({
        init() {
            this.list({page: 1, limit: 10})
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên ngành hàng',
            description: 'Ghi chú'
        },

        // pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        showAction: {
            view: false,
            edit: true,
            remove: true
        },
        selectedRow: [],

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
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "idModalUI",

        //methods
        async list(filters) {
            this.loading = true
            const response = await window.apiGetIndustry(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
                toast.success('Lấy danh sách ngành hàng thành công !')
            } else {
                toast.error('Lấy danh sách ngành hàng thất bại !')
            }
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateIndustry(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập ngành hàng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false

        },

        async create() {
            this.loading = true
            const response = await window.apiCreateIndustry(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo hàng thành thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveIndustry(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa ngành hàng thành công !')
            this.loading = false
        },

        async removeMultiple() {
            this.loading = true
            const response = await window.apiRemoveIndustryMultiple(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách ngành hàng thành công !')
            this.loading = false
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
this.resetData()
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowIndustry(id)
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
            const ids = Object.keys(this.selectedRow)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn ngành hàng cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },
    }));
});
