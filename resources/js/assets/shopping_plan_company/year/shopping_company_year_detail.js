document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_company_year_detail', (id) => ({
        async init() {
            await this.getListUser()
            this.getOrganizationRegisterYear()
            this.getInfoShoppingPlanCompanyYear()
        },

        data: [],
        selectedRow: [],
        listUser: [],
        register: [],

        async getOrganizationRegisterYear() {
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

        async getInfoShoppingPlanCompanyYear() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(id)
                if (response.success) {
                    const data = response.data.data
                    this.data.time = data.time
                    this.data.status = data.status
                    this.data.start_time = data.start_time
                    this.data.end_time = data.end_time
                    this.data.monitor_ids = data.monitor_ids
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListUser(filters) {
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
