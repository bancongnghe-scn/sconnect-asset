import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('updateShoppingPlanCompanyWeek', () => ({
        async init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.action = split.at(5)
            this.feetData()
            this.setConfigButtons()
            this.setConfigButtonsApproval()
        },

        //data
        id: null,
        action: null,
        checkedAll: false,
        data: {
            time: null,
            plan_quarter_id: null,
            month: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listUser: [],
        listSupplier: [],
        register: [],
        listPlanCompanyQuarter: [],
        selectedRow: [],
        note_disapproval: null,
        idModalConfirmDelete: 'idModalConfirmDelete',
        activeLink: {
            new: true,
            rotation: false
        },
        shoppingAssetWithAction: [],
        statusDisapproval: null,
        configButtons: [],
        configButtonsApproval: [],

        //methods
        feetData() {
            this.getInfoShoppingPlanCompanyWeek()
            this.getOrganizationRegisterWeek()
            this.getListPlanCompanyQuarter()
            this.getListSupplier()
            this.getListUser({'dept_id' : DEPT_IDS_FOLLOWERS})
            this.setConfigButtons()
            this.setConfigButtonsApproval()
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
                if (![
                    STATUS_SHOPPING_PLAN_COMPANY_NEW,
                    STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
                    STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE
                ].includes(+this.data.status)) {
                    this.syntheticShoppingAssetWithAction()
                }
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

        async sentNotificationRegister() {
            this.loading = true
            try {
                const response = await window.apiSentNotificationRegister(this.id)
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
                window.location.href = `/shopping-plan-company/week/list`
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
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

        async getListUser(filters){
            this.loading = true
            try {
                const response = await window.apiGetUser(filters)
                if (response.success) {
                    this.listUser = response.data.data
                    return
                }
                toast.error('Lấy danh sách nhân viên thất bại !')
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

        async completeShoppingPlan() {
            this.loading = true
            try {
                const response = await window.apiCompleteShoppingPlanWeek(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data.status = STATUS_SHOPPING_PLAN_COMPANY_COMPLETE
                toast.success('Hoàn thành kế hoạch mua sắm tuần thành công')
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

        confirmRemove() {
            $("#"+this.idModalConfirmDelete).modal('show');
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
                            permission: 'shopping_plan_company.sent_notification_register'
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
        }
    }))
})
