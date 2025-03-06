document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_organization_week_detail', (id) => ({
        async init() {
            this.getListAssetType()
            await this.getInfo()
            this.getRegisterAsset()
        },

        data: [],
        list_job: [],
        registers: [],
        list_asset_type: [],

        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data
                this.getJobs([this.data.organization_id])
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

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registers = response.data
                    this.registers = this.registers.map(register => ({
                        ...register,
                        receiving_time: register.receiving_time
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
    }))
})
