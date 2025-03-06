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
        table_index: [],
        list_job: [],
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
                let data = response.data
                data.unshift(POSITION_ORGANIZATION)
                this.list_job = data
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

        async sentRegister() {
            this.loading = true
            try {
                const response = await window.apiSentRegisterQuarter(this.id, this.registers)
                if (response.success) {
                    toast.success('Đăng ký mua sắm thành công')
                    this.dataTable.find(item => +item.id === +this.id).status = STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
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
                $('#modalRegister').modal('show')
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

        async getPrice(asset_type_id, position_id) {
            if (!asset_type_id || !position_id) {
                return 0
            }
            return  await this.getAllocationRateOfOrganization(this.data.organization_id, asset_type_id, position_id)
        },

        setConfigButtons() {
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

        handleShowTable(index) {
            if (index === 'expand') {
                this.table_index = [0,1,2]
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

        addRow(index) {
            this.registers[index].assets.push({
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
            this.registers[index].assets.splice(key,1)
            this.calculateApproval(index)
            this.calculateRegister(index)
        },

        calculateRegister(index) {
            let total = 0
            let price = 0
            this.registers[index].assets.forEach((asset) => {
                total += +asset.quantity_registered
                price += (asset.quantity_registered * asset.price)
            })

            this.registers[index].register.total = total
            this.registers[index].register.price = price
        },

        calculateApproval(index) {
            let total = 0
            let price = 0
            this.registers[index].assets.forEach((asset) => {
                total += +asset.quantity_approved
                price += (asset.quantity_approved * asset.price)
            })

            this.registers[index].approval.total = total
            this.registers[index].approval.price = price
        },

        calculatePrice(index) {
            let price_register = 0
            let price_approval = 0
            this.registers[index].assets.forEach((asset) => {
                price_register += (asset.quantity_registered * asset.price)
                price_approval += (asset.quantity_approved * asset.price)
            })

            this.registers[index].approval.price = price_approval
            this.registers[index].register.price = price_register
        },

        validateQuantityRegistered(value) {
            if (+value < 1) {
                toast.error('Số lượng đăng ký phải lớn hơn 0')
            }
        }
    }));
});
