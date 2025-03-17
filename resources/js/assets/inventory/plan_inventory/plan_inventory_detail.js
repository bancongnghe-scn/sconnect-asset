document.addEventListener('alpine:init', () => {
    Alpine.data('plan_inventory_detail', (id) => ({
        init() {
            this.findPlanInventory()
            this.getListUser()
            this.getListOrganization()
            this.getListAssetType()
        },

        data: {
            assets: []
        },
        listOrganization : [],
        listAssetType : [],
        listUser : [],

        async findPlanInventory() {
            this.loading = true
            try {
                const response = await window.apiFindPlanInventory(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListOrganization() {
            this.loading = true
            try {
                const response = await window.apiGetOrganizationMain()
                if (response.success) {
                    this.listOrganization = response.data.data
                    return
                }
                toast.error('Lấy danh sách đơn vị thất bại !')
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

        async getListAssetType() {
            this.loading = true
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.listAssetType = response.data.data
                    return
                }
                toast.error('Lấy danh sách loại tài sản thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }));
});
