import AirDatepicker from "air-datepicker";
document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationQuarter', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListPlanCompanyYear()
            this.watchFilters()
            this.setConfigButtons()
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

        listPlanCompanyYear: [],
        listStatus: STATUS_SHOPPING_PLAN_ORGANIZATION,
        action: null,
        id: null,
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanOrganizationQuarter(filters)
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

        async getListPlanCompanyYear(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_YEAR})
            if (response.success) {
                this.listPlanCompanyYear = response.data
            } else {
                toast.error('Lấy danh sách kế hoạch năm !')
            }
            this.loading = false
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

        async getJobs(organization_id){
            this.loading = true
            try {
                const response = await window.apiGetListJob({'org_id': organization_id})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list_job = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
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
                this.table_index = []
                this.resetData()
                await this.getInfo()
                this.getRegisterAsset()
                if (this.list_asset_type.length === 0) {
                    this.getListAssetType()
                }
                if (action === 'view') {
                    $('#modalDetailOrganization').modal('show')
                } else {
                    $('#modalRegister').modal('show')
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        setConfigButtons() {
            this.configButtonsTable = [
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER || status === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED,
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal(id, 'register'),
                        },
                    ],
                },
            ]
            this.configButtonsOrganization = [
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    ].includes(+this.dataOrganization.status),
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
                    ].includes(+this.dataOrganization.status),
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
                    ].includes(+this.dataOrganization.status),
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

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['plan_year_id', 'time', 'status'];
                const shouldCallList = watchedKeys.some((key) => value[key] !== null);
                if (shouldCallList) {
                    this.list(this.filters);
                }
            }, { deep: true });
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
