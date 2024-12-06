import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('updateShoppingPlanCompanyWeek', () => ({
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
            plan_quarter_id: null,
            month: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listUser: [],
        register: [],
        listPlanCompanyQuarter: [],
        selectedRow: [],
        note_disapproval: null,
        idModalConfirmDelete: 'idModalConfirmDelete',
        //methods
        async feetData() {
            this.getOrganizationRegisterWeek()
            await this.getListPlanCompanyQuarter()
            await this.getListUser({'dept_id' : DEPT_IDS_FOLLOWERS})
            this.getInfoShoppingPlanCompanyWeek()
        },

        async getInfoShoppingPlanCompanyWeek() {
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
                    this.data.plan_quarter_id = data.plan_quarter_id
                    this.data.month = data.month
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
                console.log('quarter')
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

        async sendAccountantApproval() {
            this.loading = true
            try {
                const response = await window.apiSendAccountantApproval(this.id)
                if (response.success) {
                    toast.success('Gửi duyệt thành công !')
                    window.location.href = `/shopping-plan-company/week/list`
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
                    window.location.href = `/shopping-plan-company/week/list`
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
