import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyWeek', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getListPlanCompanyQuarter()
            this.getListSupplier()
            this.watchFilters()
            this.setConfigButtonsTable()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Kế hoạch',
            register_time: 'Thời gian đăng ký',
            user: 'Người tạo',
            created_at: 'Ngày tạo',
            status: 'Trạng thái',
        },
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
            plan_quarter_id: null,
            time: null,
            status: null,
            limit: 10,
            page: 1
        },
        data: {
            plan_quarter_id: null,
            month: null,
            time: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
            status: null,
        },
        dataOrganization: [],
        registersOrganization: [],
        listUser: [],
        register: [],
        listSupplier: [],
        configButtons: [],
        configButtonsTable: [],
        configButtonsApproval: [],
        listPlanCompanyQuarter: [],
        shoppingAssetWithAction: [],
        list_asset_type: [],
        list_job: [],
        id: null,
        action: null,
        checkedAll: false,
        note_disapproval: null,
        statusShowDetail: [
            STATUS_SHOPPING_PLAN_COMPANY_NEW,
            STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
            STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
        ],
        activeLink: {
            new: true,
            rotation: false
        },

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompanyWeek(filters)
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
                const response = await window.apiCreateShoppingPlanCompanyWeek(this.data)
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
                const response = await window.apiRemoveShoppingPlanCompanyWeek(this.id)
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

        async removeMultiple() {
            this.loading = true
            try {
                const response = await window.apiRemoveShoppingPlanCompanyMultiple(this.id, TYPE_SHOPPING_PLAN_COMPANY_WEEK)
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

        async getListPlanCompanyQuarter(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_QUARTER, status: STATUS_SHOPPING_PLAN_COMPANY_APPROVAL})
            if (response.success) {
                this.listPlanCompanyQuarter = response.data
            } else {
                toast.error('Lấy danh sách kế hoạch quý thất bại !')
            }
            this.loading = false
        },

        async getInfoShoppingPlanCompanyWeek() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(this.id)
                if (response.success) {
                    const data = response.data.data
                    this.data = data
                    this.data.start_time = data.start_time ? format(data.start_time, 'dd/MM/yyyy') : null
                    this.data.end_time = data.end_time ? format(data.end_time, 'dd/MM/yyyy') : null
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                if ([
                    STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_APPROVAL,
                    STATUS_SHOPPING_PLAN_COMPANY_CANCEL,
                    STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR
                ].includes(+this.data.status)) {
                    this.syntheticShoppingAssetWithAction()
                }
            }
        },

        async getOrganizationRegisterWeek() {
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

        async handleShowModal(id, action) {
            this.loading = true
            try {
                this.id = id
                this.action = action
                this.resetData()
                await this.getOrganizationRegisterWeek()
                this.getInfoShoppingPlanCompanyWeek()
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

        async getInfoPlanOrganization(id){
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
            }
        },

        async getJobs(){
            this.loading = true
            try {
                const response = await window.apiGetListJob({})
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

        async getRegisterAsset(id){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registersOrganization = response.data
                    this.registersOrganization = this.registersOrganization.map(register => ({
                        ...register,
                        receiving_time: register.receiving_time ? format(register.receiving_time, 'dd/MM/yyyy') : null
                    }))
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
                toast.error('Lấy danh sách loại tài sản thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        handleShowModalDetailOrganization(id) {
            this.loading = true
            try {
                this.dataOrganization = []
                this.registersOrganization = []
                this.getInfoPlanOrganization(id)
                this.getRegisterAsset(id)
                if (this.list_asset_type.length === 0) {
                    this.getListAssetType()
                }
                if (this.list_job.length === 0) {
                    this.getJobs()
                }
                $('#modalDetailOrganization').modal('show')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListSupplier() {
            this.loading = true
            try {
                const response = await window.apiGetSupplier({})
                if (!response.success) {
                    toast.success(response.message)
                    return
                }

                this.listSupplier = response.data.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sentNotificationRegister() {
            this.loading = true
            try {
                const response = await window.apiSentNotificationRegisterWeek(this.id)
                if (response.success) {
                    toast.success('Gửi thông báo thành công !')
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_REGISTER
                    this.getOrganizationRegisterWeek()
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShopping() {
            this.loading = true
            try {
                const response = await window.apiHandleShoppingPlanWeek(this.id)
                if (response.success) {
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
                    this.register.organizations.map((item) => {
                        item.status = STATUS_SHOPPING_PLAN_ORGANIZATION_HR_HANDLE
                    })
                    toast.success('Đã chuyển sang bước xử lý')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async updatePlanWeek() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingPlanCompanyWeek(this.data, this.id)
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

        async syntheticShopping() {
            this.loading = true
            try {
                const shoppingAsset = []
                this.register.organizations.forEach((item) => {
                    item.asset_register.forEach((value) => {
                        if (Object.keys(value).length > 0) {
                            shoppingAsset.push(value)
                        }
                    })
                })
                const response = await window.apiSyntheticShoppingPlanWeek(this.id, shoppingAsset)
                if (response.success) {
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC
                    this.register.organizations.map((item) => {
                        item.status = STATUS_SHOPPING_PLAN_ORGANIZATION_HR_SYNTHETIC
                    })
                    toast.success('Đã chuyển sang bước tổng hợp')
                    this.syntheticShoppingAssetWithAction()
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sentInfoShoppingAsset() {
            const assets = []
            this.shoppingAssetWithAction.map((item) => {
                item.asset_register.new.map((value) => {
                    assets.push(value)
                })
            })

            this.loading = true
            try {
                const response = await window.apiSentInfoShoppingAsset(this.id, assets)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Lưu thông tin mua sắm thành công')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sendApprovalWeek(nextStatus) {
            this.loading = true
            try {
                const response = await window.apiSendApprovalWeek(nextStatus, this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Gửi duyệt thành công')
                if (nextStatus === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR) {
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR
                    this.shoppingAssetWithAction.map((item) => {
                        item.status = STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_HR_MANAGER
                        item.asset_register.new.map((value) => {
                            if (+value.price < PRICE_HR_APPROVAL) {
                                value.status = SHOPPING_ASSET_STATUS_PENDING_HR_MANAGER_APPROVAL
                            } else if (+value.price > PRICE_HR_APPROVAL && +value.price < PRICE_ACCOUNTANT_APPROVAL) {
                                value.status = SHOPPING_ASSET_STATUS_PENDING_ACCOUNTANT_APPROVAL
                            } else {
                                value.status = SHOPPING_ASSET_STATUS_PENDING_GENERAL_APPROVAL
                            }
                        })
                    })
                } else if (nextStatus === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL) {
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
                    this.shoppingAssetWithAction.map((item) => {
                        item.status = STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL
                    })
                } else if (nextStatus === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL) {
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL
                    this.shoppingAssetWithAction.map((item) => {
                        item.status = STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                    })
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async approvalShoppingAsset(status) {
            this.loading = true
            try {
                let ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
                ids = ids.map(Number);
                const response = await window.apiApprovalShoppingAsset(ids, status, this.note_disapproval)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Duyệt mua sắm thành công')
                this.selectedRow = []
                this.shoppingAssetWithAction.map((item) => {
                    item.asset_register.new.map((value) => {
                        if (ids.includes(value.id)) {
                            value.status = status
                        }
                    })
                })
                $("#modalNoteDisapproval").modal('hide')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        },

        showModalNoteDisapproval(statusDisable) {
            this.note_disapproval = null
            this.statusDisapproval = statusDisable
            $("#modalNoteDisapproval").modal('show')
        },

        syntheticShoppingAssetWithAction() {
            this.shoppingAssetWithAction = []
            this.register.organizations.map((item) => {
                let data = JSON.parse(JSON.stringify(item))
                data.asset_register = {
                    new: [],
                    rotation: []
                }
                item.asset_register.map((value) => {
                    if (value.length !== 0) {
                        if (+value.action === SHOPPING_ASSET_ACTION_ROTATION) {
                            data.asset_register.rotation.push(value)
                        } else {
                            data.asset_register.new.push(value)
                        }
                    }
                })

                if (data.asset_register.new.length !== 0 || data.asset_register.rotation.length !== 0) {
                    this.shoppingAssetWithAction.push(data)
                }
            })
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.shoppingAssetWithAction.forEach((item) => {
                item.asset_register.new.map((value) =>  {
                    this.selectedRow[value.id] = this.checkedAll
                })
            })
        },

        setConfigButtonsApproval() {
            this.configButtonsApproval = [
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                    permission: 'shopping_asset.hr_manager_approval',
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: () => this.approvalShoppingAsset(SHOPPING_ASSET_STATUS_HR_MANAGER_APPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.showModalNoteDisapproval(SHOPPING_ASSET_STATUS_HR_MANAGER_DISAPPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                    ]
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: () => this.approvalShoppingAsset(SHOPPING_ASSET_STATUS_ACCOUNTANT_APPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.showModalNoteDisapproval(SHOPPING_ASSET_STATUS_ACCOUNTANT_DISAPPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                    ]
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    permission: 'shopping_asset.general_approval',
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: () => this.approvalShoppingAsset(SHOPPING_ASSET_STATUS_GENERAL_APPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.showModalNoteDisapproval(SHOPPING_ASSET_STATUS_GENERAL_DISAPPROVAL),
                            disabled: () => window.checkDisableSelectRow
                        },
                    ]
                }
            ]
        },

        setConfigButtons() {
            this.configButtons = [
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_NEW,
                    buttons: [
                        {
                            text: 'Gửi thông báo',
                            class: 'btn btn-primary',
                            action: () => this.sentNotificationRegister(),
                            permission: 'shopping_plan_company.week.sent_notification_register'
                        },
                        {
                            text: 'Xóa',
                            class: 'btn btn-danger',
                            action: () => this.confirmRemove(),
                            permission: 'shopping_plan_company.week.crud'
                        },
                    ],
                },
                {
                    condition: () => [+STATUS_SHOPPING_PLAN_COMPANY_NEW, +STATUS_SHOPPING_PLAN_COMPANY_REGISTER].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-sc',
                            action: () => this.updatePlanWeek(),
                            permission: 'shopping_plan_company.week.crud'
                        },
                    ],
                },
                {
                    condition: () =>
                        [
                            STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC,
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                            STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                        ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-sc',
                            action: () => this.sentInfoShoppingAsset(),
                            permission: 'shopping_plan_company.week.synthetic_shopping'
                        },
                    ],
                },
                {
                    condition: () =>
                        +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_REGISTER &&
                        new Date() > new Date(window.formatDate(this.data.end_time)),
                    buttons: [
                        {
                            text: 'Xử lý',
                            class: 'btn btn-primary',
                            action: () => this.handleShopping(),
                            permission: 'shopping_plan_company.week.handle_shopping'
                        },
                    ],
                },
                {
                    condition: () =>
                        +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE &&
                        new Date() > new Date(window.formatDate(this.data.end_time)),
                    buttons: [
                        {
                            text: 'Tổng hợp',
                            class: 'btn btn-primary',
                            action: () => this.syntheticShopping(),
                            permission: 'shopping_plan_company.week.synthetic_shopping'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalWeek(STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR),
                            permission: 'shopping_plan_company.week.synthetic_shopping'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalWeek(STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL),
                            permission: 'shopping_asset.hr_manager_approval'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalWeek(STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
            ]
        },

        setConfigButtonsTable() {
            this.configButtonsTable = [
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_NEW,
                    permission: 'shopping_plan_company.week.crud',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) => this.handleShowModal(id, 'update'),
                        },
                        {
                            icon: 'bi bi-trash text-red',
                            action: (id) => this.confirmRemove(id),
                        },
                    ],
                },
                {
                    condition: (status) => [
                        STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
                        STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE,
                        STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC
                    ].includes(status),
                    permission: 'shopping_plan_company.week.handle_shopping',
                    buttons: [
                        {
                            icon: 'bi bi-pencil-square color-sc',
                            action: (id) =>  this.handleShowModal(id, 'update'),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_HR,
                    permission: 'shopping_asset.hr_manager_approval',
                    buttons: [
                        {
                            icon: 'bi bi-box-arrow-in-up-right text-primary',
                            action: (id) =>  this.handleShowModal(id, 'update'),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL,
                    permission: 'shopping_plan_company.accounting_approval',
                    buttons: [
                        {
                            icon: 'bi bi-box-arrow-in-up-right text-primary',
                            action: (id) =>  this.handleShowModal(id, 'update'),
                        },
                    ],
                },
                {
                    condition: (status) => status === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
                    permission: 'shopping_plan_company.general_approval',
                    buttons: [
                        {
                            icon: 'bi bi-box-arrow-in-up-right text-primary',
                            action: (id) =>  this.handleShowModal(id, 'update'),
                        },
                    ],
                },
            ]
        },

        watchFilters() {
            this.$watch('filters', (value) => {
                const watchedKeys = ['plan_quarter_id', 'status', 'time'];
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
                status: null,
                start_time: null,
                end_time: null,
                monitor_ids: [],
            }
        },

        reloadPage() {
            this.filters = {
                plan_quarter_id: null,
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
