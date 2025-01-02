import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyWeek', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getListPlanCompanyQuarter()
            this.getListSupplier()
            this.watchFilters()
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
        listUser: [],
        listPlanCompanyQuarter: [],
        register: [],
        shoppingAssetWithAction: [],
        listSupplier: [],
        id: null,
        action: null,
        configButtons: [],
        configButtonsApproval: [],
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
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
                const response = await window.apiRemoveShoppingPlanCompany(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return;
                }
                $("#"+this.idModalConfirmDelete).modal('hide')
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
                $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
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
                }
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

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        },

        syntheticShoppingAssetWithAction() {
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
                            permission: 'shopping_plan_company.sent_notifi_register'
                        },
                        {
                            text: 'Xóa',
                            class: 'btn btn-danger',
                            action: () => this.confirmRemove(),
                            permission: 'shopping_plan_company.crud'
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
                            permission: 'shopping_plan_company.crud'
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
                            permission: 'shopping_plan_company.synthetic_shopping'
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
                            permission: 'shopping_plan_company.handle_shopping'
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
                            permission: 'shopping_plan_company.synthetic_shopping'
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
                            permission: 'shopping_plan_company.synthetic_shopping'
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

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
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
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },
    }));
});
