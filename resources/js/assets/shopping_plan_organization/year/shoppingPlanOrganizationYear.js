document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationYear', () => ({
        init() {
            this.list({page:1, limit:10})
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
        dataOrganization: {
            name: null,
            organization_name: null,
            organization_id: null,
            start_time : null,
            end_time : null,
            status: null,
            register_time: null
        },
        registersOrganization : [
            {
                assets: [],
                register: {total:0, price: 0},
                approval: {total:0, price: 0},
                month: 1
            }
        ],
        list_asset_type: [],
        table_index: [],
        list_job: [],

        action: null,
        id: null,
        idModalInfo: "idModalInfo",
        configButtonsTable: [],
        configButtonsOrganization: [],

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

        async getJobs(organization_id){
            this.loading = true
            try {
                const response = await window.apiGetListJob({'org_id': organization_id})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                let data = response.data
                data.unshift(POSITION_ORGANIZATION)
                this.list_job = data
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
                this.dataOrganization = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.getJobs([this.dataOrganization.organization_id])
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(this.id)
                if (response.success) {
                    this.registersOrganization = response.data
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

        async sentRegister() {
            this.loading = true
            try {
                const response = await window.apiSentRegisterYear(this.id, this.registersOrganization)
                if (response.success) {
                    toast.success('Đăng ký mua sắm thành công')
                    this.list(this.filters)
                    $('#modalRegister').modal('hide')
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async saveReviewRegisterAsset() {
            this.loading = true
            try {
                const response = await window.apiSaveReviewRegisterAsset(this.id, this.registersOrganization)
                if (response.success) {
                    toast.success('Lưu thông tin phê duyệt thành công')
                    this.dataOrganization.status = STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
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
                    this.dataOrganization.status = type === ORGANIZATION_TYPE_APPROVAL
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

        async getAllocationRateOfOrganization(organization_id, asset_type_id, position_id){
            try {
                const type = position_id === POSITION_ORGANIZATION.id ? TYPE_ALLOCATION_RATE_ORGANIZATION : TYPE_ALLOCATION_RATE_POSITION
                const response = type === TYPE_ALLOCATION_RATE_ORGANIZATION ?
                    await window.apiGetAllocationRateOfOrganization(organization_id, asset_type_id, type) :
                    await window.apiGetAllocationRateOfOrganization(organization_id, asset_type_id, type, position_id)

                if (!response.success) {
                    toast.error(response.message)
                    return 0
                }

                if (response.data.data.length === 0) {
                    toast.error('Chưa có cấu hình định mức cho loại tài sản này !')
                }

                return response.data.data?.price ?? 0

            } catch (e) {
                toast.error(e)
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

        handleShowTable(index) {
            if (index === 'expand') {
                this.table_index = [0,1,2,3,4,5,6,7,8,9,10,11]
                return;
            }

            if (index === 'decrease') {
                this.table_index = []
                return;
            }

            if (!this.table_index.includes(index)) {
                this.table_index.push(index)
            } else {
                this.table_index = this.table_index.filter(item => item !== index);
            }
        },

        async getPrice(asset_type_id, position_id) {
            if (!asset_type_id || !position_id) {
                return 0
            }
            return  await this.getAllocationRateOfOrganization(this.dataOrganization.organization_id, asset_type_id, position_id)
        },

        validateQuantityRegistered(value) {
            if (+value < 1) {
                toast.error('Số lượng đăng ký phải lớn hơn 0')
            }
        },

        addRow(index) {
            this.registersOrganization[index].assets.push({
                id_fake: Date.now() + Math.random(),
                asset_type_id: null,
                job_id: null,
                price: null,
                description: null,
                quantity_registered: null,
                quantity_approved: null
            })
        },

        deleteRow(index, key) {
            this.registersOrganization[index].assets.splice(key,1)
            this.calculateApproval(index)
            this.calculateRegister(index)
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

        calculateApproval(index) {
            let total = 0
            let price = 0
            this.registersOrganization[index].assets.forEach((asset) => {
                total += +asset.quantity_approved
                price += (asset.quantity_approved * asset.price)
            })

            this.registersOrganization[index].approval.total = total
            this.registersOrganization[index].approval.price = price
        },

        calculatePrice(index) {
            let price_register = 0
            let price_approval = 0
            this.registersOrganization[index].assets.forEach((asset) => {
                price_register += (asset.quantity_registered * asset.price)
                price_approval += (asset.quantity_approved * asset.price)
            })

            this.registersOrganization[index].approval.price = price_approval
            this.registersOrganization[index].register.price = price_register
        },

        calculateRegister(index) {
            let total = 0
            let price = 0
            this.registersOrganization[index].assets.forEach((asset) => {
                total += +asset.quantity_registered
                price += (asset.quantity_registered * asset.price)
            })

            this.registersOrganization[index].register.total = total
            this.registersOrganization[index].register.price = price
        },

        setConfigButtons() {
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
            this.configButtonsTable = [
                {
                    condition: (status, startTime, endTime) =>
                        (
                            [STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER, STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED].includes(status)
                            && new Date() >= new Date(window.formatDate(startTime))
                            && new Date() <= new Date(window.formatDate(endTime))
                        ) || status === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL,
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal(id, 'register'),
                        },
                    ],
                },
            ]
        },

        resetData() {
            this.dataOrganization = {
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
