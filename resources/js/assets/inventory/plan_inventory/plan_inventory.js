document.addEventListener('alpine:init', () => {
    Alpine.data('plan_inventory', () => ({
        init() {
            this.getListPlanInventory({page: 1, limit: 10})
            this.getListOrganization()
            this.getListAssetType()
            this.getListUser()
            this.watchFilters()
        },

        //dataTable
        dataTable: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        selectedRow: [],

        //data
        filters: {
            name: null,
            start_time: null,
            end_time: null,
            status: null,
            from : null,
            limit: 10,
            page: 1
        },
        data: {
            name: null,
            start_time: null,
            end_time: null,
            type_inventory: TYPE_INVENTORY_NOT_AUTO,
            note: null,
            sent_notification: null,
            organization_ids: [],
            asset_type_ids: [],
            user_ids: []
        },
        title: null,
        action: null,
        id: null,
        listOrganization: [],
        listAssetType: [],
        listUser: [],

        //methods
        async getListPlanInventory(filters){
            this.loading = true
            try {
                const response = await window.apiGetPlanInventory(filters)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async createPlanInventory() {
            this.loading = true
            try {
                const response = await window.apiCreatePlanInventory(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo kế hoạch kiểm kê thành công !')
                $('#modalCreatePlanInventory').modal('hide')
                this.getListPlanInventory(this.filters)
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

        async handleShowModalInsert() {
            this.resetData()
            $('#modalCreatePlanInventory').modal('show');
        },

        async deletePlanInventory() {
            this.loading = true
            try {
                const response = await window.apiDeletePlanInventory(this.id)
                if (response.success) {
                    this.getListPlanInventory(this.filters)
                    $('#idModalConfirmDelete').modal('hide')
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        confirmRemovePlanInventory(multiple = false, id = null) {
            if (multiple) {
                this.id = []
                this.selectedRow.filter((value, key) => {
                    if (value) {
                        this.id.push(+key)
                    }
                })
            } else {
                this.id = id
            }
            $("#idModalConfirmDelete").modal('show');
        },

        watchFilters() {
            this.$watch('filters.start_time', (value) => {
                if (value !== null && this.filters.end_time !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.end_time', (value) => {
                if (this.filters.start_time !== null && value !== null) {
                    this.list(this.filters)
                }
            })

            this.$watch('filters.status', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            })
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
                name: null,
                start_time: null,
                end_time: null,
                type_inventory: TYPE_INVENTORY_NOT_AUTO,
                note: null,
                sent_notification: null,
                organization_ids: [],
                asset_type_ids: [],
                user_ids: []
            }
        },

        reloadPage() {
            this.filters = {
                name: null,
                start_time: null,
                end_time: null,
                status: null,
                from : null,
                limit: 10,
                page: 1
            }

            this.list(this.filters)
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.dataTable.forEach((item) => {
                if (item.status === STATUS_INVENTORY_NEW) {
                    this.selectedRow[item.id] = this.checkedAll
                }
            })
        },
    }));
});
