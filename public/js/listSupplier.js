document.addEventListener('alpine:init', () => {
    Alpine.data('supplier', () => ({
        init() {
            this.getListSupplier({
                page: 1,
                limit: 10
            })
            this.getListIndustry({})
            this.getListAssetType({})
            this.initSelect2InModal()
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
            code_name: null,
            status: [],
            industries_id: [],
            page: 1,
            limit: 10
        },
        supplier: {
            name: null,
            code: null,
            website: null,
            contact: null,
            address: null,
            industry_ids: [],
            asset_type_ids: [],
            tax_code: null,
            description: null,
            meta_data: {
                payment_terms: {
                    debt_day: null,
                    discount_period : null,
                    discount_rate: null,
                    deposit_amount : null,
                    description: null
                }
            }
        },
        listIndustry : [],
        listAssetType : [],
        titleAction: null,
        action: null,
        id: null,
        idModalConfirmDelete: "deleteSupplier",
        activeLink: {
            payment_terms : true,
            payment_account : false
        },

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

        async getListAssetType(filters) {
            this.loading = true
            const response = await window.apiGetAssetType(filters)
            if (response.success) {
                this.listAssetType = response.data.data.data
            } else {
                toast.error('Lấy danh sách loại tài sản thất bại !')
            }
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
            this.reloadPage()
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

        async handShowModalSupplierUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.titleAction = 'Thêm mới'
                window.generateShortCode().then(code => {
                    this.supplier.code = code
                })
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
            this.supplier = {
                name: null,
                contact: null,
                address: null,
                industries: [],
                tax_code: null,
                description: null,
                level: null,
                note_evaluate: null,
                meta_data: {
                    payment_terms: {
                        debt_day: null,
                        discount_period: null,
                        discount_rate: null,
                        deposit_amount: null,
                        description: null
                    }
                }
            }
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        reloadPage() {
            const filters = {
                code_name: null,
                status: [],
                industries_id: [],
                page: 1,
                limit: this.limit
            }

            this.getListSupplier(filters)
        },

        initSelect2InModal() {
            $('#modalSupplierUI').on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $('#modalSupplierUI') // Gán dropdown vào modal
                });
            });
            $('.select2').on('select2:select select2:unselect', (event) => {
                if (event.target.id === 'industrySelect2') {
                    this.supplier.industry_ids = $(event.target).val();
                } else if (event.target.id === 'assetTypeSelect2') {
                    this.supplier.asset_type_ids = $(event.target).val();
                }
            });
        },
    }));
});
