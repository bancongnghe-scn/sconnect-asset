import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyQuarter', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getListPlanCompanyYearComplete()
            this.watchFilters()
            this.setConfigButton()
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
            plan_year_id: null,
            time: null,
            status: null,
            limit: 10,
            page: 1
        },
        data: {
            plan_year_id: null,
            time: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },

        id: null,
        note_disapproval: null,
        showModal: false,
        listStatus: STATUS_SHOPPING_PLAN_COMPANY,
        listUser: [],
        register: [],
        listPlanCompanyYearComplete: [],
        configButtonsTable: [],

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompanyQuarter(filters)
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

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateShoppingPlanCompanyQuarter(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo kế hoạch mua sắm quý thành công !')
                $('#idModalInsert').modal('hide');
                this.resetData()
                this.reloadPage()
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
                $("#modalUpdate").modal('hide')
                toast.success('Xóa kế hoạch mua sắm năm thành công !')
                this.list(this.filters)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async removeMultiple() {
            this.loading = true
            try {
                const response = await window.apiRemoveShoppingPlanCompanyMultiple(this.id, TYPE_SHOPPING_PLAN_COMPANY_QUARTER)
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

        async getListPlanCompanyYearComplete(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_YEAR, status: STATUS_SHOPPING_PLAN_COMPANY_APPROVAL})
            if (response.success) {
                this.listPlanCompanyYearComplete = response.data
            } else {
                toast.error('Lấy danh sách kế hoạch năm !')
            }
            this.loading = false
        },

        async getOrganizationRegisterQuarter() {
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

        async getInfoShoppingPlanCompanyQuarter() {
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
                    this.data.plan_year_id = data.plan_year_id
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async updatePlanQuarter() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingPlanCompanyQuarter(this.data, this.id)
                if (response.success) {
                    toast.success('Cập nhật kế hoạch mua sắm năm thành công !')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sendAccountantApproval() {
            this.loading = true
            try {
                const response = await window.apiSendAccountantApproval(this.id)
                if (response.success) {
                    toast.success('Gửi duyệt thành công !')
                    window.location.href = `/shopping-plan-company/quarter/list`
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sendManagerApproval() {
            this.loading = true
            try {
                const response = await window.apiSendManagerApproval(this.id)
                if (response.success) {
                    toast.success('Gửi duyệt thành công !')
                    window.location.href = `/shopping-plan-company/quarter/list`
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async generalApprovalShoppingPlanCompany(type) {
            this.loading = true
            try {
                const response = await window.apiGeneralApprovalShoppingPlanCompany(this.id, type, this.note_disapproval)
                if (response.success) {
                    toast.success('Bạn đã duyệt thành công !')
                    if (type === GENERAL_TYPE_APPROVAL_COMPANY) {
                        this.data.status = STATUS_SHOPPING_PLAN_COMPANY_APPROVAL
                    } else {
                        this.data.status = STATUS_SHOPPING_PLAN_COMPANY_CANCEL
                        $("#modalNoteDisapprovalPlanCompany").modal('hide')
                    }
                    this.getOrganizationRegisterQuarter()
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sentNotificationRegister() {
            this.loading = true
            try {
                const response = await window.apiSentNotificationRegister(this.id)
                if (response.success) {
                    toast.success('Gửi thông báo thành công !')
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_REGISTER
                    this.getOrganizationRegisterQuarter()
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModal(action, id = null) {
            this.loading = true
            this.showModal = true
            try {
                this.id = id
                this.action = action
                this.resetData()
                if (action === 'create') {
                    $('#idModalInsert').modal('show')
                    return
                }

                this.getOrganizationRegisterQuarter()
                await this.getInfoShoppingPlanCompanyQuarter()
                if (action === 'view') {
                    $('#modalDetail').modal('show')
                } else {
                    $('#modalUpdate').modal('show')
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.showModal = false
            }
        },

        setConfigButton() {
            this.configButtonsTable = [
                {
                    condition: (status) => STATUS_SHOPPING_PLAN_COMPANY_NEW === status,
                    permission: 'shopping_plan_company.crud',
                    buttons: [
                        {
                            icon: 'bi bi-trash text-red',
                            action: (id) => this.confirmRemove(id),
                        },
                    ],
                },
                {
                    condition: (status) => [STATUS_SHOPPING_PLAN_COMPANY_NEW,STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(status),
                    permission: 'shopping_plan_company.crud',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal('update', id),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal( 'update', id),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    permission: 'shopping_plan_company.general_approval',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal('update', id),
                        },
                    ],
                },
                {
                    condition: () => true,
                    permission: true,
                    buttons: [
                        {
                            icon: 'bi bi-eye text-info',
                            action: (id) => this.handleShowModal('view', id),
                        },
                    ],
                },
            ]
            this.configButtons = [
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW,
                    buttons: [
                        {
                            text: 'Gửi thông báo',
                            class: 'btn btn-primary',
                            action: () => this.sentNotificationRegister(),
                            permission: 'shopping_plan_company.sent_notification_register'
                        },
                        {
                            text: 'Xóa',
                            class: 'btn btn-danger',
                            action: (id) => this.confirmRemove(id),
                            permission: 'shopping_plan_company.crud'
                        },
                    ],
                },
                {
                    condition: () => true,
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-sc',
                            action: () => this.updatePlanQuarter(),
                            permission: 'shopping_plan_company.crud'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER &&
                        new Date() > new Date(window.formatDate(this.data.end_time)),
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendAccountantApproval(),
                            permission: 'shopping_plan_company.sent_account_approval'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendManagerApproval(),
                            permission: 'shopping_plan_company.sent_manager_approval'
                        },
                    ],
                },
                {
                    condition: () => STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL === +this.data.status,
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn btn-sc',
                            action: () => this.generalApprovalShoppingPlanCompany(GENERAL_TYPE_APPROVAL_COMPANY),
                            permission: 'shopping_plan_company.general_approval'
                        },
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.showModalNoteDisapprovalShoppingCompany(),
                            permission: 'shopping_plan_company.general_approval'
                        },
                    ],
                }
            ]
            this.configButtonsApproval = [
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: () => this.accountApprovalMultipleShoppingPlanOrganization(ORGANIZATION_TYPE_APPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.showModalNoteDisapprovalMultiple(),
                            disabled: () => window.checkDisableSelectRow
                        },
                    ]
                },
            ]
        },

        showModalNoteDisapprovalShoppingCompany() {
            this.note_disapproval = null
            $("#modalNoteDisapprovalPlanCompany").modal('show')
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['plan_year_id', 'time', 'status'];
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
                plan_year_id: null,
                time: null,
                start_time: null,
                end_time: null,
                monitor_ids: [],
            }
        },

        reloadPage() {
            this.filters = {
                plan_year_id: null,
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
