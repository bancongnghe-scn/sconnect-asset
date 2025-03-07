document.addEventListener('alpine:init', () => {
    Alpine.data('order_list', () => ({
        init() {
            this.getTotalStatusOrder()
            this.list(this.filters)
            this.watch()
            this.watchFilters()
            this.$watch('tab_status', (value) => {
                this.reloadPage()
            })
        },

        //dataTable
        dataTable: [],

        // pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        selectedRow: [],
        listIndustry: [],

        //data
        filters: {
            code_name: null,
            status: ORDER_STATUS_NEW,
            created_at: null,
            page: 1,
            limit: 10
        },
        typeCreateOrder: ORDER_TYPE_CREATE_WITH_PLAN,
        data: {
            shopping_plan_company_id: null,
            supplier_id: null,
            name: null,
            type: ORDER_TYPE_CREATE_WITH_PLAN,
            purchasing_manager_id: null,
            delivery_date: null,
            delivery_location: null,
            contact_person: null,
            contract_info: null,
            payment_time: null,
            status: ORDER_STATUS_NEW,
            shipping_costs: null,
            other_costs: null,
            shopping_assets_order: [],
            industry_ids: [],
        },
        listShoppingPlanCompany: [],
        listSupplier: [],
        listUser: [],
        listAssetType: [],
        listOrganization: [],
        id: null,
        showModal : false,
        reason: null,
        tab_status: ORDER_STATUS_NEW,
        total_order: {
            new: 0,
            transit: 0,
            delivered: 0,
            warehoused: 0,
            cancel: 0,
        },

        //methods
        async list(filters) {
            this.loading = true
            try {
                const response = await window.apiGetListOrder({
                    code_name: filters.code_name,
                    created_at: filters.created_at,
                    status: [filters.status],
                    page: filters.page,
                    limit: filters.limit
                })
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                const data = response.data
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
                const response = await window.apiCreateOrder(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo đơn hàng thành công')
                $('#modalInsert').modal('hide')
                this.list(this.filters)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async remove() {
            this.loading = true
            try {
                const ids = Array.isArray(this.id) ? this.id : [this.id]
                const response = await window.apiRemoveOrder(ids, this.reason)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Xóa đơn hàng thành công')
                $('#modalReason').modal('hide')
                this.dataTable.filter((item) => {
                    if (ids.includes(item.id)) {
                        item.status = ORDER_STATUS_CANCEL
                    }
                })
                this.selectedRow = []
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalInsert() {
            this.resetData()
            $('#modalInsert').modal('show')
            if (this.listAssetType.length === 0) {
                this.getListAssetType()
            }
            if (this.listOrganization.length === 0) {
                this.getListOrganization()
            }
            if (this.listUser.length === 0) {
                this.getListUser()
            }
            if (this.listIndustry.length === 0) {
                this.getListIndustry()
            }
        },

        async getListShoppingPlanCompany() {
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompany(
                    {status: STATUS_SHOPPING_PLAN_COMPANY_COMPLETE, type: TYPE_SHOPPING_PLAN_COMPANY_WEEK}
                )
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.listShoppingPlanCompany = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getTotalStatusOrder() {
            try {
                const response = await window.apiGetTotalStatusOrder()
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data
                data.forEach((item) => {
                    switch (item.status) {
                        case ORDER_STATUS_NEW:
                            this.total_order.new = item.total
                            break
                        case ORDER_STATUS_TRANSIT:
                            this.total_order.transit = item.total
                            break
                        case ORDER_STATUS_DELIVERED:
                            this.total_order.delivered = item.total
                            break
                        case ORDER_STATUS_WAREHOUSED:
                            this.total_order.warehoused = item.total
                            break
                        case ORDER_STATUS_CANCEL:
                            this.total_order.cancel = item.total
                            break
                    }
                })
            } catch (e) {
                toast.error(e)
            }
        },

        async getListSupplier() {
            this.loading = true
            try {
                const response = await window.apiGetSupplier({})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                this.listSupplier = response.data.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getSupplierOfShoppingPlanWeek(id) {
            this.loading = true
            try {
                const response = await window.apiGetSupplierOfShoppingPlanWeek(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.listSupplier = response.data.data
                if (this.listSupplier.length === 0) {
                    toast.error('Không còn nhà cung cấp nào ứng với kế hoạch !')
                }
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

        async getShoppingAssets(){
            this.loading = true
            try {
                let response = await window.apiGetListShoppingAsset({
                    shopping_plan_company_id: this.data.shopping_plan_company_id,
                    supplier_id: this.data.supplier_id,
                    status: [SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL, SHOPPING_ASSET_STATUS_GENERAL_APPROVAL]
                })
                if (response.success) {
                    this.data.shopping_assets_order = response.data.data
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

        async getListOrganization() {
            this.loading = true
            try {
                const response = await window.apiGetOrganization({})
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

        async getListIndustry() {
            this.loading = true
            const response = await window.apiGetIndustry()
            if (response.success) {
                this.listIndustry = response.data.data
            } else {
                toast.error('Lấy danh sách ngành hàng thất bại !')
            }
            this.loading = false
        },

        async getIndustriesForSupplier(id) {
            try {
                const response = await window.apiShowSupplier(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
                this.data.industry_ids = data.industry_ids
            } catch (e) {
                toast.error(e)
            }
        },

        watch() {
            this.$watch('data.type', (value) => {
                if (value !== null) {
                    if (+value === ORDER_TYPE_CREATE_WITH_PLAN && this.listShoppingPlanCompany.length < 1) {
                        this.getListShoppingPlanCompany()
                    } else if (+value === ORDER_TYPE_CREATE_WITH_NOT_PLAN) {
                        this.listSupplier = []
                        this.getListSupplier();
                    }
                }
            });

            this.$watch('data.shopping_plan_company_id', (value) => {
                if (value !== null && +this.data.type === ORDER_TYPE_CREATE_WITH_PLAN) {
                    this.listSupplier = []
                    this.data.supplier_id = null
                    this.getSupplierOfShoppingPlanWeek(value);
                }
            });

            this.$watch('data.supplier_id', (value) => {
                if (value !== null) {
                    if (+this.data.type === ORDER_TYPE_CREATE_WITH_PLAN) {
                        this.getShoppingAssets()
                    }
                    this.getIndustriesForSupplier(value)
                }
            });
        },

        watchFilters() {
            this.$watch('filters.created_at', (value) => {
                if (value !== null) {
                    this.list(this.filters)
                }
            });
        },

        confirmRemove(multiple = false, id = null) {
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
            $("#confirmRemove").modal('show');
            this.reason = null
        },

        addRows() {
            let rows = {
                name: null,
                vat_rate: null,
                price: null,
                asset_type_id: null,
                description: null,
                organization_id: null,
                quantity_approved: null,
            }
            this.data.shopping_assets_order.push(rows)
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
                shopping_plan_company_id: null,
                supplier_id: null,
                name: null,
                type: this.typeCreateOrder,
                purchasing_manager_id: null,
                delivery_date: null,
                delivery_location: null,
                contact_person: null,
                contract_info: null,
                payment_time: null,
                status: ORDER_STATUS_NEW,
                shipping_costs: null,
                other_costs: null,
                shopping_assets_order: [],
                industry_ids: [],
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                code_name: null,
                status: this.tab_status,
                created_at: null,
                page: 1,
                limit: 10
            }
        },
    }));
});
