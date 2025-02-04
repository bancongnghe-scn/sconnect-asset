import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyYear', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.watchFilters()
            this.setConfigButtonsTable()
        },

        //dataTable
        dataTable: [],
        selectedRow: [],

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,

        //data
        filters: {
            time: null,
            status: null,
            limit: 10,
            page: 1
        },

        data: {
            time: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },

        id: null,
        listUser: [],
        register: [],
        configButtonsTable: [],

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompanyYear(filters)
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

        async remove() {
            this.loading = true
            try {
                const response = await window.apiRemoveShoppingPlanCompany(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return;
                }
                $("#idModalConfirmDelete").modal('hide')
                toast.success('Xóa kế hoạch mua sắm năm thành công !')
                this.list(this.filters)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateShoppingPlanCompanyYear(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo kế hoạch mua sắm năm thành công !')
                $('#idModalInsert').modal('hide');
                this.resetData()
                this.reloadPage()
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListUser(filters){
            this.loading = true
            const response = await window.apiGetUser(filters)
            if (response.success) {
                this.listUser = response.data.data
            } else {
                toast.error('Lấy danh sách nhân viên thất bại !')
            }
            this.loading = false
        },

        async handleShowModal(id, action) {
            this.loading = true
            try {
                this.id = id
                this.action = action
                this.resetData()
                await this.getOrganizationRegisterYear()
                await this.getInfoShoppingPlanCompanyYear()
                if (action === 'view') {
                    $('#modalDetail').modal('show')
                } else {
                    this.setConfigButtons()
                    this.setConfigButtonsApproval()
                    $('#modalUpdate').modal('show')
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getOrganizationRegisterYear() {
            this.loading = true
            try {
                const response = await window.getOrganizationRegister(this.id)
                if (response.success) {
                    this.register = response.data.data
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getInfoShoppingPlanCompanyYear() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(this.id)
                if (response.success) {
                    const data = response.data.data
                    this.data.time = data.time
                    this.data.status = data.status
                    this.data.start_time = data.start_time ? format(data.start_time, 'dd/MM/yyyy') : null
                    this.data.end_time = data.end_time ? format(data.end_time, 'dd/MM/yyyy') : null
                    this.data.monitor_ids = data.monitor_ids
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async removeMultiple() {
            this.loading = true
            try {
                const response = await window.apiRemoveShoppingPlanCompanyMultiple(this.id, TYPE_SHOPPING_PLAN_COMPANY_YEAR)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                $("#idModalConfirmDeleteMultiple").modal('hide')
                this.list(this.filters)
                this.selectedRow = []
                toast.success('Xóa danh sách kế hoạch mua sắm thành công !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        setConfigButtonsTable() {
            this.configButtonsTable = [
                {
                    condition: (status) => [STATUS_SHOPPING_PLAN_COMPANY_NEW,STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(status),
                    permission: 'shopping_plan_company.crud',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => window.location.href = `/shopping-plan-company/year/update/${id}`,
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_NEW,
                    permission: 'shopping_plan_company.crud',
                    buttons: [
                        {
                            icon: 'bi bi-trash text-red',
                            action: (id) => this.confirmRemove(id),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            icon: 'fa-solid fa-pen-to-square',
                            action: (id) =>  window.location.href = `/shopping-plan-company/year/update/${id}`,
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    permission: 'shopping_plan_company.general_approval',
                    buttons: [
                        {
                            icon: 'fa-solid fa-pen-to-square',
                            action: (id) =>  window.location.href = `/shopping-plan-company/year/update/${id}`,
                        },
                    ],
                },
            ]
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['time', 'status'];
                const shouldCallList = watchedKeys.some((key) => value[key] !== null);

                if (shouldCallList) {
                    this.list(this.filters);
                }
            }, { deep: true });
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn kế hoạch mua sắm cần xóa !')
                return
            }

            $("#idModalConfirmDeleteMultiple").modal('show');
            this.id = ids
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
                time: null,
                status: null,
                start_time: null,
                end_time: null,
                monitor_ids: [],
            }
        },

        reloadPage() {
            this.filters = {
                time: null,
                status: null,
                limit: 10,
                page: 1
            }

            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#idModalConfirmDelete").modal('show');
            this.id = id
        },
    }));
});
