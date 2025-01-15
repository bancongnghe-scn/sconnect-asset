document.addEventListener('alpine:init', () => {
    Alpine.data('allocation_rate_position', () => ({
        init() {
            this.list(this.filters)
            this.getListPosition()
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
            position_id: null,
            type: TYPE_ALLOCATION_RATE_POSITION,
            page: 1,
            limit: 5,
        },
        listPosition: [],
        data: {
            organization_id: null,
            position_id: null,
            configs: [],
            type: TYPE_ALLOCATION_RATE_POSITION
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

        async getListPosition(){
            this.loading = true
            try {
                const response = await window.apiGetListJob({})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.listPosition = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModal(action, organizationId = null, positionId = null) {
            this.action = action
            this.resetData()
            if (action === 'update') {
                const response = await window.apiGetListAllocationRate({
                    organization_id: organizationId,
                    position_id: positionId,
                    type: TYPE_ALLOCATION_RATE_POSITION
                })
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data.data.data[0]
                this.data.type = TYPE_ALLOCATION_RATE_POSITION
            }
            $('#modalUIPosition').modal('show')
        },

         async create() {
            this.loading = true
            try {
                const response = await window.apiCreateAllocationRate(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalUIPosition').modal('hide')
                this.list(this.filters)
                toast.success('Tạo định mức cấp phát thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
         },

        handleShowModalConfirmRemove(organizationId = null, positionId = null, multiple = true) {
            if (multiple) {
                const organizationIds = []
                const positionIds = []
                Object.keys(this.selectedRow).filter((key) => {
                    if (this.selectedRow[key]) {
                        const values = key.split("_");
                        const organizationId = parseInt(values[0], 10);
                        const positionId = parseInt(values[1], 10);
                        organizationIds.push(organizationId)
                        positionIds.push(positionId)
                    }
                })
                this.dataRemove = {
                    type: TYPE_ALLOCATION_RATE_POSITION,
                    organization_id: organizationIds,
                    position_id: positionIds,
                }
            } else {
                this.dataRemove = {
                    type: TYPE_ALLOCATION_RATE_POSITION,
                    organization_id: [organizationId],
                    position_id: [positionId]
                }
            }
            $('#modalConfirmRemove').modal('show')
        },

        async remove() {
            this.loading = true
            try {
                const response = await window.apiDeleteAllocationRate(this.dataRemove)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalConfirmRemove').modal('hide')
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
                $('#modalUIPosition').modal('hide')
                this.list(this.filters)
                toast.success('Cập nhật định mức cấp phát thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
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

        resetData() {
            this.data = {
                organization_id: null,
                position_id: null,
                configs: [],
                type: TYPE_ALLOCATION_RATE_POSITION
            }
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['organization_id', 'position_id'];
                const shouldCallList = watchedKeys.some((key) => value[key] !== null);

                if (shouldCallList) {
                    this.list(this.filters);
                }
            }, { deep: true });
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.dataTable.forEach((item) => this.selectedRow[item.organization_id + '_' + item.position_id] = this.checkedAll)
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
                organization_id: null,
                position_id: null,
                type: TYPE_ALLOCATION_RATE_POSITION,
                page: 1,
                limit: 5,
            }
            this.list(this.filters)
        },
    }));
});
