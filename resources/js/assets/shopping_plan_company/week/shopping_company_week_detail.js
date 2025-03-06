document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_company_week_detail', (id) => ({
        async init() {
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getListSupplier()
            await this.getOrganizationRegisterWeek()
            this.getInfoShoppingPlanCompanyWeek()
        },

        data: {
            plan_quarter_id: null,
            month: null,
            time: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
            status: null,
        },
        register: [],
        listUser: [],
        selectedRow: [],
        listSupplier: [],
        statusShowDetail: [
            STATUS_SHOPPING_PLAN_COMPANY_NEW,
            STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
            STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
        ],
        shoppingAssetWithAction: [],
        activeLink: {
            new: true,
            rotation: false
        },

        async getOrganizationRegisterWeek() {
            this.loading = true
            try {
                const response = await window.getOrganizationRegister(id)
                if (response.success) {
                    this.register = response.data.data
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getInfoShoppingPlanCompanyWeek() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(id)
                if (response.success) {
                    const data = response.data.data
                    this.data = data
                    this.data.start_time = data.start_time
                    this.data.end_time = data.end_time
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                if ([
                    STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_CANCEL,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                    STATUS_SHOPPING_PLAN_COMPANY_COMPLETE
                ].includes(+this.data.status)) {
                    this.syntheticShoppingAssetWithAction()
                }
            }
        },

        syntheticShoppingAssetWithAction() {
            this.shoppingAssetWithAction = []
            this.register.organizations.map((item) => {
                let data = JSON.parse(JSON.stringify(item))
                data.asset_register = {
                    new: [],
                    rotation: []
                }
                item.asset_register.map((value) => {
                    if (value.length !== 0) {
                        if (+value.action === SHOPPING_ASSET_ACTION_ROTATION) {
                            data.asset_register.rotation.push(value)
                        } else {
                            data.asset_register.new.push(value)
                        }
                    }
                })

                if (data.asset_register.new.length !== 0 || data.asset_register.rotation.length !== 0) {
                    this.shoppingAssetWithAction.push(data)
                }
            })
        },

        async getListUser(filters){
            this.loading = true
            const response = await window.apiGetUser(filters)
            if (response.success) {
                this.listUser = response.data.data
            } else {
                toast.error('Lấy danh sách nhân viên thất bại !')
            }
            this.loading = false
        },

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        },

        async getListSupplier() {
            this.loading = true
            try {
                const response = await window.apiGetSupplier({})
                if (!response.success) {
                    toast.success(response.message)
                    return
                }

                this.listSupplier = response.data.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }))
})
