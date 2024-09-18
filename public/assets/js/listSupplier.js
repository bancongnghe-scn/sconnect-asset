document.addEventListener('alpine:init', () => {
    Alpine.data('supplier', () => ({
        init() {
            this.getListSupplier({
                page: 1,
                limit: 10
            })
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên nhà cung cấp',
            industries: 'Ngành hàng',
            contact: 'Liên hệ',
            address: 'Địa chỉ',
            website: 'Website',
            level: 'Xếp hạng',
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
            page: 1,
            limit: 10
        },
        industry: {
            name: null,
            description: null,
        },
        titleAction: null,
        action: null,
        id: null,
        idModalConfirmDelete: "deleteIndustry",

        //methods
        async getListSupplier(filters) {
            this.loading = true
            const response = await window.apiGetSupplier(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total
                toast.success('Lấy danh sách nhà cung cấp thành công !')
            } else {
                toast.error('Lấy danh sách nhà cung cấp thất bại !')
            }
            this.loading = false
        },

        async editIndustry() {
            this.loading = true
            const response = await window.apiUpdateIndustry(this.industry, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập nhà cung cấp thành công !')
            $('#modalIndustryUI').modal('hide');
            this.resetDataIndustry()
            await this.getListIndustry(this.filters)
            this.loading = false
        },

        async removeIndustry() {
            this.loading = true
            const response = await window.apiRemoveIndustry(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa nhà cung cấp thành công !')

            this.getListIndustry(this.filters)

            this.loading = false
        },

        async createIndustry() {
            this.loading = true
            const response = await window.apiCreateIndustry(this.industry)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo nhà cung cấp thành công !')
            $('#modalIndustryUI').modal('hide');
            this.resetDataIndustry()
            await this.getListIndustry(this.filters)
            this.loading = false
        },

        async handShowModalIndustryUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.titleAction = 'Thêm mới'
            } else {
                this.titleAction = 'Cập nhật'
                this.id = id
                const response = await window.apiShowIndustry(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.industry.name = data.name
                this.industry.description = data.description
            }

            $('#modalIndustryUI').modal('show');
        },

        handleIndustryUI() {
            if (this.action === 'create') {
                this.createIndustry()
            } else {
                this.editIndustry()
            }
        },

        changePage(page) {
            this.filters.page = page
            this.getListIndustry(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListIndustry(this.filters)
        },

        resetDataIndustry() {
            this.industry.name = null
            this.industry.description = null
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },
    }));
});
