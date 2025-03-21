document.addEventListener('alpine:init', () => {
    Alpine.data('allocation_rate', () => ({
        init() {
            this.getListOrganization()
            this.getListAssetType()
        },
        activeLink: {
            position: true,
            organization: false
        },
        listOrganization: [],
        listAssetType: [],

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
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

        async getListAssetType() {
            this.loading = true
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.listAssetType = response.data.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }));
});
