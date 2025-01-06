document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationWeek', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListPlanCompanyQuarter()
            this.getListAssetType()
            this.watchFilters()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Kế hoạch',
            register_time: 'Thời gian đăng ký',
            user: 'Người tạo',
            created_at: 'Ngày tạo',
            status: 'Trạng thái',
        },

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

        listPlanCompanyQuarter: [],
        list_asset_type: [],
        list_job: [],
        registers : [],
        action: null,
        id: null,

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanOrganizationWeek(filters)
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

        async getListPlanCompanyQuarter(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_QUARTER, status: STATUS_SHOPPING_PLAN_COMPANY_APPROVAL})
            if (response.success) {
                this.listPlanCompanyQuarter = response.data
            } else {
                toast.error('Lấy danh sách kế hoạch quý thất bại !')
            }
            this.loading = false
        },

        async handleShowModal(id, action) {
            this.loading = true
            this.resetData()
            this.registers = []
            this.action = action
            this.id = id
            try {
                await this.getInfo()
                this.getRegisterAsset()
                if (action === 'view') {
                    $('#modalDetail').modal('show');
                } else {
                    $('#modalRegister').modal('show');
                }
            } catch (e) {

            } finally {

            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(this.id)
                if (response.success) {
                    this.registers = response.data
                    this.registers = this.registers.map(register => ({
                        ...register,
                        receiving_time: register.receiving_time ? format(register.receiving_time, 'dd/MM/yyyy') : null
                    }))
                    return
                }
                toast.error(response.message)
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
                await this.getJobs([this.data.organization_id])
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

        async getJobs(organization_id){
            this.loading = true
            try {
                const response = await window.apiGetListJob({org_id: organization_id})
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

        async sentRegister() {
            this.loading = true
            try {
                const response = await window.apiSentRegisterWeek(this.id, this.registers)
                if (response.success) {
                    toast.success('Đăng ký mua sắm thành công')
                    this.getRegisterAsset()
                    if (+this.data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER) {
                        this.data.status = STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                    }
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        addRow() {
            this.registers.push({
                id_fake: Date.now() + Math.random(),
                asset_type_id: null,
                measure: null,
                quantity_registered: null,
                job_id: null,
                receiving_time: null,
                description: null
            })
        },

        resetData() {
            this.data = {
                name: null,
                organization_name: null,
                organization_id: null,
                start_time : null,
                end_time : null,
                status: null,
                register_time: null
            }
        },

        deleteRow(index) {
            this.registers.splice(index,1)
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['plan_quarter_id', 'status', 'time'];
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
