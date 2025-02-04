document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationYear', () => ({
        init() {
            this.list({page:1, limit:10})
            this.watchFilters()
            this.setConfigButtonsTable()
        },

        //dataTable
        dataTable: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        //data
        filters: {
            time: null,
            status: null,
            limit: 10,
            page: 1
        },
        data: {
            name: null,
            organization_name: null,
            organization_id: null,
            start_time : null,
            end_time : null,
            status: null,
            register_time: null
        },
        registers : [
            {
                assets: [],
                register: {total:0, price: 0},
                approval: {total:0, price: 0},
                month: 1
            }
        ],
        list_asset_type: [],

        action: null,
        id: null,
        idModalInfo: "idModalInfo",
        configButtonsTable: [],
        configButtons: [],

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanOrganizationYear(filters)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.getJobs([this.data.organization_id])
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(this.id)
                if (response.success) {
                    this.registers = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListAssetType() {
            this.loading = true
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.list_asset_type = response.data.data
                    return
                }
                toast.error('Lấy danh sách loại tài sản thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModal(id, action) {
            this.loading = true
            try {
                this.id = id
                this.action = action
                this.resetData()
                this.getInfo()
                this.getRegisterAsset()
                if (this.list_asset_type.length === 0) {
                    this.getListAssetType()
                }
                if (action === 'view') {
                    $('#modalDetail').modal('show')
                } else {
                    this.setConfigButtons()
                    this.setConfigButtonsApproval()
                    this.setConfigApprovalOrganizations()
                    $('#modalUpdate').modal('show')
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async saveReviewRegisterAsset() {
            this.loading = true
            try {
                const response = await window.apiSaveReviewRegisterAsset(this.id, this.registers)
                if (response.success) {
                    toast.success('Lưu thông tin phê duyệt thành công')
                    this.data.status = STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async accountApprovalShoppingPlanOrganization(id, type) {
            this.loading = true
            try {
                const response = await window.apiAccountApprovalShoppingPlanOrganization([id], type)
                if (response.success) {
                    this.data.status = type === ORGANIZATION_TYPE_APPROVAL
                        ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                    toast.success('Duyệt thành công !')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        setConfigButtons() {
            this.configButtons = [
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-primary',
                            action: () => this.saveReviewRegisterAsset(),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: (id) => this.accountApprovalShoppingPlanOrganization(id, ORGANIZATION_TYPE_APPROVAL),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: (id) => this.accountApprovalShoppingPlanOrganization(id, ORGANIZATION_TYPE_DISAPPROVAL),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
            ]
        },

        setConfigButtonsTable() {
            this.configButtonsTable = [
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal(id, 'view'),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED,
                    buttons: [
                        {
                            icon: 'fa-regular fa-pen-to-square color-sc',
                            action: (id) => this.handleShowModal(id, 'register'),
                        },
                    ],
                },
            ]
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['time', 'status'];
                const shouldCallList = watchedKeys.some((key) => value[key] !== null);

                if (shouldCallList) {
                    this.list(this.filters);
                }
            }, { deep: true });
        },

        resetData() {
            this.data = {
                name: null,
                organization_name: null,
                organization_id: null,
                start_time: null,
                end_time: null,
                status: null,
                register_time: null
            }
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        reloadPage() {
            this.filters = {
                time: null,
                status: null,
                limit: 10,
                page: 1
            }
            this.list(this.filters)
        },
    }));
});
