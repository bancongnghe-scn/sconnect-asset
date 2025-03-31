document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_arise_organization_list', () => ({
        init() {
            this.getListShoppingArise(this.filters)
            this.watchFilters()
        },

        //pagination
        dataTable: [],
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        filters: {
            name: null,
            start_time: null,
            end_time: null,
            status: null,
            page: 1,
            limit: 10,
            type: GET_SHOPPING_ARISE_TYPE_ORGANIZATION
        },
        selectedRow: [],
        list_asset_type: [],
        list_job: [],
        data: {
            name: null,
            assets: []
        },

        async getListShoppingArise(filters) {
            this.loading = true
            try {
                const response = await window.apiGetListShoppingArise(filters)
                if (response.success) {
                    const data = response.data
                    this.dataTable = data.data.data || []
                    this.totalPages = data.data.last_page
                    this.currentPage = data.data.current_page
                    this.from = data.data.from ?? 0
                    this.to = data.data.to ?? 0
                    this.total = data.data.total ?? 0
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async createShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiCreateShoppingArise(this.data)
                if (response.success) {
                    toast.success('Tạo đề xuất phát sinh thành công !')
                    this.getListShoppingArise(this.filters)
                    $('#modalCreateShoppingArise').modal('hide')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async deleteShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiDeleteShoppingArise(this.id)
                if (response.success) {
                    toast.success('Xóa đề xuất phát sinh thành công !')
                    this.getListShoppingArise(this.filters)
                    $('#modalConfirmDelete').modal('hide')
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
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListJobOfManager(){
            this.loading = true
            try {
                const response = await window.apiGetListJobOfManager()
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

        handleShowModalCreate() {
            this.data = {
                name: null,
                assets: []
            }

            if (this.list_job.length === 0) {
                this.getListJobOfManager()
            }

            if (this.list_asset_type.length === 0) {
                this.getListAssetType()
            }

            $('#modalCreateShoppingArise').modal('show')
        },

        confirmRemove(type, id = null) {
            if (type === 'multiple') {
                this.id = []
                this.selectedRow.filter((value, key) => {
                    if (value) {
                        this.id.push(+key)
                    }
                })
            } else {
                this.id = [id]
            }

            $('#modalConfirmDelete').modal('show')
        },

        addRowAsset() {
            this.data.assets.push({
                asset_type_id: null,
                quantity_registered: null,
                job_id: null,
                receiving_time: null,
                description: null
            })
        },

        watchFilters() {
            this.$watch('filters.start_time', (value) => {
                if (value && this.filters.end_time) {
                    this.getListShoppingArise(this.filters)
                }
            });

            this.$watch('filters.end_time', (value) => {
                if (value && this.filters.start_time) {
                    this.getListShoppingArise(this.filters)
                }
            });

            this.$watch('filters.status', (value) => {
                if (value) {
                    this.getListShoppingArise(this.filters)
                }
            });
        },

        reloadPage() {
            this.filters = {
                name: null,
                start_time: null,
                end_time: null,
                status: null,
                type: GET_SHOPPING_ARISE_TYPE_ORGANIZATION,
                page: this.page,
                limit: this.limit,
            }
            this.getListShoppingArise(this.filters)
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            if (this.dataTable.length === 0) {
                return
            }
            this.dataTable.forEach((item) => {
                if (+item.status === STATUS_SHOPPING_ARISE_NEW) {
                    this.selectedRow[item.id] = this.checkedAll
                }
            })
        },

        changePage(page) {
            this.filters.page = page
            this.getListShoppingArise(this.filters)
        },

        changeLimit(limit) {
            this.filters.limit = limit
            this.getListShoppingArise(this.filters)
        },
    }))
})
