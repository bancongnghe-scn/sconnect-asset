document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_company_quarter_detail', (id) => ({
        init() {
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getOrganizationRegisterQuarter()
            this.getInfoShoppingPlanCompanyQuarter()
        },

        data: {
            name: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listUser: [],
        register: [],
        selectedRow: [],

        async getOrganizationRegisterQuarter() {
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

        async getInfoShoppingPlanCompanyQuarter() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(id)
                if (response.success) {
                    this.data = response.data.data
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
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
    }))
})
