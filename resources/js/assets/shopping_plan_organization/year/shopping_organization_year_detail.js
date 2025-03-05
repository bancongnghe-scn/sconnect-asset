document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_organization_year_detail', (id) => ({
        async init() {
            this.getListAssetType()
            await this.getInfo()
            this.getRegisterAsset()
        },

        list_job: [],
        table_index: [],
        dataOrganization: [],
        registersOrganization: [],
        configButtonsOrganization: [],

        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(id)
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
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registersOrganization = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },

        async getListAssetType() {
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
            }
        },

        async getJobs(organization_id){
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
    }))
})
