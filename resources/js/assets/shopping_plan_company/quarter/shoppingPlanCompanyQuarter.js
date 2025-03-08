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
        registersOrganization: [],
        dataOrganization: [],
        list_asset_type: [],
        list_job : [],
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
                toast.error(response.message)
            }
            this.loading = false
        },

        async getListPlanCompanyYearComplete(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_YEAR, status: STATUS_SHOPPING_PLAN_COMPANY_APPROVAL})
            if (response.success) {
                this.listPlanCompanyYearComplete = response.data
            } else {
                toast.error(response.message)
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
                    this.data.start_time = data.start_time
                    this.data.end_time = data.end_time
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

        async sentNotificationRegister(type= 'sent_to_detail',id = null) {
            this.loading = true
            if (type === 'sent_to_table') {
                this.id = id
            }
            try {
                const response = await window.apiSentNotificationRegister(this.id)
                if (response.success) {
                    toast.success('Gửi thông báo thành công !')
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_REGISTER
                    if (type === 'sent_to_detail') {
                        this.getOrganizationRegisterQuarter()
                    }
                    this.list(this.filters)
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async accountApprovalShoppingPlanOrganization(id, type) {
            this.loading = true
            try {
                const response = await window.apiAccountApprovalShoppingPlanOrganization([id], type, this.note_disapproval)
                if (response.success) {
                    let organization = this.register.organizations.find((item) => +item.id === +id);
                    if (type === ORGANIZATION_TYPE_APPROVAL) {
                        organization.status = STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                    } else {
                        organization.status = STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
                        $("#modalNoteDisapproval").modal('hide')
                    }
                    toast.success('Duyệt thành công !')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async accountApprovalMultipleShoppingPlanOrganization(type) {
            this.loading = true
            try {
                let ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
                ids = ids.map(Number);
                const response = await window.apiAccountApprovalShoppingPlanOrganization(ids, type, this.note_disapproval)
                if (response.success) {
                    this.register.organizations.filter(function (item) {
                        if (ids.includes(item.id)) {
                            item.status = type === ORGANIZATION_TYPE_APPROVAL
                                ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
                        }
                    });
                    this.selectedRow = []
                    if (type === ORGANIZATION_TYPE_DISAPPROVAL) {
                        $("#modalNoteDisapprovalMultiple").modal('hide')
                    }
                    toast.success('Duyệt thành công !')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.register.organizations.forEach((item) => {
                if ([
                    STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                    STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                ].includes(+item.status)) {
                    this.selectedRow[item.id] = this.checkedAll
                }
            })
        },

        async getInfoOrganization(id) {
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.dataOrganization = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.getJobs([this.data.organization_id])
            }
        },

        async getRegisterAssetOrganization(id) {
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registersOrganization = response.data
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

        async getJobs(organization_id){
            this.loading = true
            try {
                const response = await window.apiGetListJob({'org_id': organization_id})
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

        async sentRegisterAgain(id) {
            this.loading = true
            try {
                const response = await window.apiSentRegisterAgain(id)
                if (response.success) {
                    this.list(this.filters)
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
                $('#modalUpdate').modal('show')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.showModal = false
            }
        },

        handleShowTable(index) {
            if (!this.table_index.includes(index)) {
                this.table_index.push(index)
            } else {
                this.table_index = this.table_index.filter(item => item !== index);
            }
        },

        calculateApproval(index) {
            let total = 0
            let price = 0
            this.registersOrganization[index].assets.forEach((asset) => {
                total += +asset.quantity_approved
                price += (asset.quantity_approved * asset.price)
            })

            this.registersOrganization[index].approval.total = total
            this.registersOrganization[index].approval.price = price
        },

        setConfigButton() {
            this.configButtonsTable = [
                {
                    condition: (status) =>
                        STATUS_SHOPPING_PLAN_COMPANY_CANCEL === status
                        && this.permission.includes('shopping_plan_company.sent_register_again')
                    ,
                    buttons: [
                        {
                            icon: 'bi bi-repeat color-sc',
                            action: (id) => this.sentRegisterAgain(id),
                        },
                    ],
                },
                {
                    condition: (status) =>
                        STATUS_SHOPPING_PLAN_COMPANY_NEW === status
                        && this.permission.includes('shopping_plan_company.year_quarter.sent_notification_register')
                    ,
                    buttons: [
                        {
                            icon: 'bi bi-send text-primary',
                            action: (id) => this.sentNotificationRegister('sent_to_table',id),
                        },
                    ],
                },
                {
                    condition: (status) =>
                        STATUS_SHOPPING_PLAN_COMPANY_NEW === status
                        && this.permission.includes('shopping_plan_company.year_quarter.crud')
                    ,
                    buttons: [
                        {
                            icon: 'bi bi-trash text-red',
                            action: (id) => this.confirmRemove(id),
                        },
                    ],
                },
                {
                    condition: (status) =>
                        (
                            [STATUS_SHOPPING_PLAN_COMPANY_NEW, STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(status)
                            && this.permission.includes('shopping_plan_company.year_quarter.crud')
                        )
                        ||
                        (
                            status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
                            && this.permission.includes('shopping_plan_company.accounting_approval')
                        )
                        ||
                        (
                            status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
                            && this.permission.includes('shopping_plan_company.general_approval')
                        )
                    ,
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal('update', id),
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
                            permission: 'shopping_plan_company.year_quarter.sent_notification_register'
                        },
                        {
                            text: 'Xóa',
                            class: 'btn btn-danger',
                            action: (id) => this.confirmRemove(id),
                            permission: 'shopping_plan_company.year_quarter.crud'
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
                            permission: 'shopping_plan_company.year_quarter.crud'
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
            this.configButtonApprovalOrganizationTable = [
                {
                    condition: (status) => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    ].includes(status),
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            icon: 'bi bi-check fs-4 color-sc',
                            action: (id) => this.accountApprovalShoppingPlanOrganization(id, ORGANIZATION_TYPE_APPROVAL),
                        },
                        {
                            icon: 'bi bi-x fs-4 text-red',
                            action: (id) => this.showModalNoteDisapproval(id),
                        },
                    ],
                }
            ]
        },

        showModalNoteDisapprovalShoppingCompany() {
            this.note_disapproval = null
            $("#modalNoteDisapprovalPlanCompany").modal('show')
        },

        showModalNoteDisapproval(id) {
            this.id_organization = id
            this.note_disapproval = null
            $("#modalNoteDisapproval").modal('show')
        },

        showModalNoteDisapprovalMultiple() {
            this.note_disapproval = null
            $("#modalNoteDisapprovalMultiple").modal('show')
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
