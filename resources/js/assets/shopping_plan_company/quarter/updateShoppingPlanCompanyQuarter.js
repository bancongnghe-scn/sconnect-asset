import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('updateShoppingPlanCompanyQuarter', () => ({
        async init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.action = split.at(5);
            this.feetData()
        },

        //data
        id: null,
        id_organization: null,
        action: null,
        checkedAll: false,
        data: {
            time: null,
            plan_year_id: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listUser: [],
        register: [],
        listQuarter: LIST_QUARTER,
        listPlanCompanyYear: [],
        selectedRow: [],
        note_disapproval: null,
        idModalConfirmDelete: 'idModalConfirmDelete',
        //methods
        async feetData() {
            this.getOrganizationRegisterQuarter()
            await this.getListPlanCompanyYear()
            await this.getListUser({'dept_id' : DEPT_IDS_FOLLOWERS})
            this.getInfoShoppingPlanCompanyQuarter()
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
                console.log('info')
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

        async getListPlanCompanyYear(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_YEAR})
            if (response.success) {
                this.listPlanCompanyYear = response.data
                console.log('year')
            } else {
                toast.error('Lấy danh sách kế hoạch năm !')
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
                window.location.href = `/shopping-plan-company/quarter/list`
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getOrganizationRegisterQuarter() {
            this.loading = true
            try {
                const response = await window.getOrganizationRegisterYearQuarter(this.id)
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
                console.log('users')
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
                        organization.status = STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
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
                                ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                        }
                    });
                    this.selectedRow = []
                    if ( type === ORGANIZATION_TYPE_DISAPPROVAL) {
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

        showModalNoteDisapproval(id) {
            this.id_organization = id
            this.note_disapproval = null
            $("#modalNoteDisapproval").modal('show')
        },

        showModalNoteDisapprovalMultiple() {
            this.note_disapproval = null
            $("#modalNoteDisapprovalMultiple").modal('show')
        },

        showModalNoteDisapprovalShoppingCompany() {
            this.note_disapproval = null
            $("#modalNoteDisapprovalPlanCompany").modal('show')
        },

        confirmRemove() {
            $("#"+this.idModalConfirmDelete).modal('show');
        },

        selectedAll() {
            this.checkedAll = !this.checkedAll
            this.register.organizations.forEach((item) => {
                this.selectedRow[item.id] = this.checkedAll
            })
        }
    }))
})