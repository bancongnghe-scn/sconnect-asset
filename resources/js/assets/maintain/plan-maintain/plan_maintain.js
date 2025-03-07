document.addEventListener('alpine:init', () => {
    Alpine.data('plan_maintain', () => ({
        init() {
            this.list(this.filters)
            this.getListSupplier()
            this.getListOrganization()
            this.getListUser()
            this.watchFilters()
        },

        //table
        dataTable: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        filters: {
            name_code: null,
            start_time: null,
            end_time: null,
            supplier_id: null,
            status: null,
            page: 1,
            limit: 10
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
        listSupplier: [],
        listOrganization: [],
        listUser: [],
        checkedAll: false,
        selectedRow: [],
        selectedRowAssetMaintain: [],
        checkedAllAssetMaintain: false,
        title: null,
        action: null,
        id: null,

        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetPlanMaintain(filters)
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
                this.$dispatch('total-plan-maintain', data.total ?? 0)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreatePlanMaintain(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $('#modalInsertPlanMaintain').modal('hide')
                this.list(this.filters)
                toast.success('Tạo kế hoạch bảo dưỡng thành công !')
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

        async getListSupplier() {
            this.loading = true
            const response = await window.apiGetSupplier({})
            if (response.success) {
                this.listSupplier = response.data.data.data
            } else {
                toast.error('Lấy danh sách nhà cung cấp thất bại !')
            }
            this.loading = false
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

        async getAssetMaintainForOrganization(newOrganizationIds, oldOrganizationIds) {
            if (newOrganizationIds.length === 0) {
                this.data.assets_maintain = []
                return
            }

            const organizationIdsRemove = oldOrganizationIds.filter(item => !newOrganizationIds.includes(item));
            if (organizationIdsRemove.length > 0) {
                this.data.assets_maintain = this.data.assets_maintain.filter((item) => !organizationIdsRemove.includes(item.organization_id))
            }
            const organizationIdsNew = newOrganizationIds.filter(item => !oldOrganizationIds.includes(item));
            if (organizationIdsNew.length > 0) {
                let data = await this.getAssetNeedMaintain({organization_ids: organizationIdsNew})
                if (this.action === 'create') {
                    data.filter((item) => {item.status = 0})
                }
                this.data.assets_maintain = [...this.data.assets_maintain, ...data]
            }
        },

        async getAssetNeedMaintain(filters) {
            this.loading = true
            try {
                const response = await window.apiGetAssetNeedMaintain(filters)
                if (!response.success) {
                    return toast.error(response.message)
                }

                return response.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getInfoPlanMaintain() {
            this.loading = true
            try {
                const response = await window.apiGetInfoPlanMaintain(this.id)
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

        async updatePlanMaintain() {
            this.loading = true
            try {
                const response = await window.apiUpdatePlanMaintain(this.id, this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                toast.success('Cập nhật kế hoạch bảo dưỡng thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async deletePlanMaintain() {
            this.loading = true
            try {
                const response = await window.apiDeletePlanMaintain(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                toast.success('Xóa kế hoạch bảo dưỡng thành công !')
                this.dataTable = this.dataTable.filter((item) => +item.id !== +this.id)
                $('#modalUpdatePlanMaintain').modal('hide')
                $('#modalConfirmDelete').modal('hide')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async completePlanMaintain() {
            this.loading = true
            try {
                const response = await window.apiCompletePlanMaintain(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.list(this.filters)
                $('#modalUpdatePlanMaintain').modal('hide')
                $('#modalConfirmCompletePlan').modal('hide')
                toast.success('Hoàn thành kế hoạch bảo dưỡng thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalUI(action, id = null) {
            this.action = action
            this.id = id
            this.resetData()

            if (action === 'create') {
                $('#modalInsertPlanMaintain').modal('show')
            } else {
                await this.getInfoPlanMaintain()
                $('#modalUpdatePlanMaintain').modal('show')
            }
        },

        handleShowModalConfirmDelete(id = null) {
           if (id !== null) {
               this.id = id
           }
           $('#modalConfirmDelete').modal('show')
        },

        async completeAssetMaintain() {
            const ids = Object.keys(this.selectedRowAssetMaintain).filter(key => this.selectedRowAssetMaintain[key] === true)
            if (ids.length < 1) {
                toast.error('Bạn phải chọn tài sản cần hoàn thành bảo dưỡng')
                return
            }
            this.loading = true
            try {
                let configs = this.data.assets_maintain.filter((item) => ids.includes(String(item.id)))
                const response = await window.apiCompleteAssetMaintain(configs)
                if (!response.success) {
                    toast.error(response.message)
                }

                configs.filter((item) => item.status = STATUS_ASSET_MAINTAIN_COMPLETE)
                toast.success('Hoàn thành tài sản bảo dưỡng thành công !')
                this.selectedRowAssetMaintain = []
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
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

            this.$watch('filters.supplier_id', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            })
        },

        reloadPage() {
            this.filters = {
                name_code: null,
                start_time: null,
                end_time: null,
                supplier_id: null,
                status: null,
                page: 1,
                limit: 10
            }
            this.list(this.filters)
        },

        resetData() {
           this.data = {
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
           }
        },

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        selectedAllAssetMaintain() {
            this.checkedAllAssetMaintain = !this.checkedAllAssetMaintain
            this.data.assets_maintain.forEach((item) => {
                if (+item.status === STATUS_ASSET_MAINTAINING) {
                    this.selectedRowAssetMaintain[item.id] = this.checkedAllAssetMaintain
                }
            })
        }
    }));
});
