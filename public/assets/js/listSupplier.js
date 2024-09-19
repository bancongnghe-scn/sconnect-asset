document.addEventListener('alpine:init', () => {
    Alpine.data('supplier', () => ({
        init() {
            this.getListSupplier({
                page: 1,
                limit: 10
            })
            this.getListIndustry({})
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
            level: null,
            industry_id: null,
            page: 1,
            limit: 10
        },
        supplier: {
            name: null,
            contact: null,
            address: null,
            industries: [],
            tax_code: null,
            description: null,
            level: null,
            note_evaluate: null
        },
        listIndustry : [],
        titleAction: null,
        action: null,
        id: null,
        idModalConfirmDelete: "deleteSupplier",

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

        async getListIndustry(filters) {
            this.loading = true
            const response = await window.apiGetIndustry(filters)
            if (response.success) {
                this.listIndustry = response.data.data
            } else {
                toast.error('Lấy danh sách ngành hàng thất bại !')
            }
            this.loading = false
        },

        async editSupplier() {
            this.loading = true
            const response = await window.apiUpdateSupplier(this.supplier, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập nhà cung cấp thành công !')
            $('#modalSupplierUI').modal('hide');
            this.resetDataSupplier()
            await this.getListSupplier(this.filters)
            this.loading = false
        },

        async removeSupplier() {
            this.loading = true
            const response = await window.apiRemoveSupplier(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa nhà cung cấp thành công !')

            this.getListSupplier(this.filters)

            this.loading = false
        },

        async createSupplier() {
            this.loading = true
            const response = await window.apiCreateSupplier(this.supplier)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo nhà cung cấp thành công !')
            $('#modalSupplierUI').modal('hide');
            this.resetDataSupplier()
            await this.getListSupplier(this.filters)
            this.loading = false
        },

        async handShowModalSupplierUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.titleAction = 'Thêm mới'
            } else {
                this.titleAction = 'Cập nhật'
                this.id = id
                const response = await window.apiShowSupplier(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.supplier.name = data.name
                this.supplier.description = data.description
            }

            $('#modalSupplierUI').modal('show');
        },

        handleSupplierUI() {
            if (this.action === 'create') {
                this.createSupplier()
            } else {
                this.editSupplier()
            }
        },

        changePage(page) {
            this.filters.page = page
            this.getListSupplier(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListSupplier(this.filters)
        },

        resetDataSupplier() {
            this.supplier.name = null
            this.supplier.description = null
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },
    }));
});
