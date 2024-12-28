document.addEventListener('alpine:init', () => {
    Alpine.data('supplier', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.getListIndustry()
            this.getListAssetType()
            window.initSelect2Modal(this.idModalUI)
            this.onChangeSelect2()
        },

        //dataTable
        dataTable: [],
        columns: {
            code: 'Mã',
            name: 'Tên nhà cung cấp',
            industries: 'Ngành hàng',
            contact: 'Số điện thoại',
            address: 'Địa chỉ/Website',
            status: 'Đánh giá',
            contract_user: 'Người liên hệ',
        },
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
            code_name: null,
            status: [],
            industry_ids: [],
            page: 1,
            limit: 10
        },
        data: {
            name: null,
            code: null,
            contract_user: null,
            contact: null,
            address: null,
            tax_code: null,
            email: null,
            description: null,
            industry_ids: [],
            asset_type_ids: [],
            meta_data: {
                payment_terms: {
                    debt_day: null,
                    discount_period : null,
                    discount_rate: null,
                    deposit_amount : null,
                    description: null
                },
                payment_account: {
                    number: null,
                    name: null,
                    owner: null,
                    branch: null,
                    province: null
                }
            }
        },
        listIndustry : [],
        listAssetType : [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "idModalUI",
        activeLink: {
            payment_terms : true,
            payment_account : false
        },
        status: {
           1: 'Chờ phê duyệt'
        },

        //methods
        async list(filters) {
            this.loading = true
            const response = await window.apiGetSupplier(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } else {
                toast.error('Lấy danh sách nhà cung cấp thất bại !')
            }
            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateSupplier(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo nhà cung cấp thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
            this.loading = false
        },


        async edit() {
            this.loading = true
            const response = await window.apiUpdateSupplier(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập nhà cung cấp thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false

        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveSupplier(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa nhà cung cấp thành công !')
            this.loading = false
        },

        async removeMultiple() {
            this.loading = true
            const response = await window.apiRemoveSupplierMultiple(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách nhà cung cấp thành công !')
            this.loading = false
        },

        async getListIndustry() {
            this.loading = true
            const response = await window.apiGetIndustry()
            if (response.success) {
                this.listIndustry = response.data.data
            } else {
                toast.error('Lấy danh sách ngành hàng thất bại !')
            }
            this.loading = false
        },

        async getListAssetType() {
            this.loading = true
            const response = await window.apiGetAssetType({})
            if (response.success) {
                this.listAssetType = response.data.data
            } else {
                toast.error('Lấy danh sách loại tài sản thất bại !')
            }
            this.loading = false
        },

        async handleShowModalUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
                window.generateShortCode().then(code => {
                    this.data.code = code
                })
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowSupplier(id)
                if (!response.success) {
                    this.loading = false
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data
            }

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
                code: null,
                contract_user: null,
                contact: null,
                address: null,
                tax_code: null,
                description: null,
                email: null,
                industry_ids: [],
                asset_type_ids: [],
                meta_data: {
                    payment_terms: {
                        debt_day: null,
                        discount_period : null,
                        discount_rate: null,
                        deposit_amount : null,
                        description: null
                    },
                    payment_account: {
                        number: null,
                        name: null,
                        owner: null,
                        branch: null,
                        province: null
                    }
                }
            }
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn ngành hàng cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                code_name: null,
                status: [],
                industry_ids: [],
                page: 1,
                limit: 10
            }
            $('#industriesFilter').val([]).change()
            $('#statusFilter').val([]).change()
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'industrySelect2') {
                    this.data.industry_ids = value
                } else if (event.target.id === 'assetTypeSelect2') {
                    this.data.asset_type_ids = value
                } else if (event.target.id === 'industriesFilter') {
                    this.filters.industry_ids = value
                } else if (event.target.id === 'statusFilter') {
                    this.filters.status = value
                }
            });
        },

        handleMetaData(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        }
    }));
});
