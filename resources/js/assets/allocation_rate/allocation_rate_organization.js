
document.addEventListener('alpine:init', () => {
    Alpine.data('allocation_rate_organization', () => ({
        init() {
            this.list(this.filters)
            this.watchFilters()
        },

        dataTable: [],
        selectedRow: [],
        checkedAll: false,

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        filters: {
            organization_id: null,
            type: TYPE_ALLOCATION_RATE_ORGANIZATION,
            page: 1,
            limit: 5,
        },
        data: {
            organization_id: null,
            configs: [],
            type: TYPE_ALLOCATION_RATE_ORGANIZATION
        },
        action: null,
        dataRemove: [],

        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetListAllocationRate(filters)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.dataTable = data.data
                this.totalPages = data.last_page
                this.currentPage = data.current_page
                this.total = data.total ?? 0
                this.from = data.from ?? 0
                this.to = data.to ?? 0
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateAllocationRate(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalUIOrganization').modal('hide')
                this.list(this.filters)
                toast.success('Tạo định mức cấp phát thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async remove() {
            this.loading = true
            try {
                const response = await window.apiDeleteAllocationRate(this.dataRemove)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalConfirmRemoveOrganization').modal('hide')
                this.list(this.filters)
                toast.success('Xóa định mức cấp phát thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async edit() {
            this.loading = true
            try {
                const response = await window.apiUpdateAllocationRate(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalUIOrganization').modal('hide')
                this.list(this.filters)
                toast.success('Cập nhật định mức cấp phát thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModal(action, organizationId = null) {
            this.action = action
            this.resetData()
            if (action === 'update') {
                const response = await window.apiGetListAllocationRate({
                    organization_id: organizationId,
                    type: TYPE_ALLOCATION_RATE_ORGANIZATION
                })
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data.data[0]
                this.data.type = TYPE_ALLOCATION_RATE_ORGANIZATION
            }
            $('#modalUIOrganization').modal('show')
        },

        handleShowModalConfirmRemove(organizationId = null, multiple = true) {
            if (multiple) {
                const organizationIds = []
                Object.keys(this.selectedRow).filter((key) => {
                    if (this.selectedRow[key]) {
                        organizationIds.push(key)
                    }
                })
                this.dataRemove = {
                    type: TYPE_ALLOCATION_RATE_ORGANIZATION,
                    organization_id: organizationIds,
                }
            } else {
                this.dataRemove = {
                    type: TYPE_ALLOCATION_RATE_ORGANIZATION,
                    organization_id: [organizationId],
                }
            }
            $('#modalConfirmRemoveOrganization').modal('show')
        },

        watchFilters() {
            this.$watch('filters.organization_id', (value) => {
                if (value !== null) {
                    this.list(this.filters);
                }
            });
        },

        addRowTable() {
            this.data.configs = this.data.configs || []
            this.data.configs.push({
                asset_type_id: null,
                level: null,
                price: null,
                description: null
            })
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.dataTable.forEach((item) => this.selectedRow[item.organization_id] = this.checkedAll)
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        resetData() {
            this.data = {
                organization_id: null,
                configs: [],
                type: TYPE_ALLOCATION_RATE_ORGANIZATION
            }
        },

        reloadPage() {
            this.filters = {
                organization_id: null,
                type: TYPE_ALLOCATION_RATE_ORGANIZATION,
                page: 1,
                limit: 5,
            }
            this.list(this.filters)
        },
    }));
});
