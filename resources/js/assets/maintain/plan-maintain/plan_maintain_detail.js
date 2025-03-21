document.addEventListener('alpine:init', () => {
    Alpine.data('plan_maintain_detail', (id) => ({
        init() {
            this.getListUser()
            this.getListSupplier()
            this.getListOrganization()
            this.getInfoPlanMaintain()
        },

        data: {
            name: null,
            start_time: null,
            end_time: null,
            maintain_costs: null,
            note: null,
            sent_notification: false,
            organization_ids: [],
            supplier_ids: [],
            user_ids: [],
            assets_maintain: []
        },
        listUser: [],
        listSupplier: [],
        listOrganization: [],

        async getInfoPlanMaintain() {
            this.loading = true
            try {
                const response = await window.apiGetInfoPlanMaintain(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.data = response.data.data
                this.data.sent_notification = Boolean(this.data.sent_notification)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListUser(){
            this.loading = true
            try {
                const response = await window.apiGetUser({})
                if (response.success) {
                    this.listUser = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListSupplier() {
            this.loading = true
            const response = await window.apiGetSupplier({})
            if (response.success) {
                this.listSupplier = response.data.data.data
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        async getListOrganization() {
            this.loading = true
            try {
                const response = await window.apiGetOrganizationMain()
                if (response.success) {
                    this.listOrganization = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }))
})
