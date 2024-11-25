import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('updateShoppingPlanCompanyYear', () => ({
        async init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.action = split.at(5);
            this.getOrganizationRegisterYear()
            this.initDateRangePicker()
            this.initYearPicker()
            await this.getListUser({
                'dept_id' : DEPT_IDS_FOLLOWERS
            })
            this.getInfoShoppingPlanCompanyYear()
        },

        //data
        id: null,
        action: null,
        checkedAll: false,
        data: {
            time: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listUser: [],
        register: [],
        selectedRow: [],
        dateRangePicker: null,
        idModalConfirmDelete: 'idModalConfirmDelete',
        //methods
        async getInfoShoppingPlanCompanyYear() {
            this.loading = true
            try {
                const response = await window.apiShowShoppingPlanCompany(this.id)
                if (response.success) {
                    const data = response.data.data
                    this.dateRangePicker.selectDate([window.convertDateString(data.start_time), window.convertDateString(data.end_time)]);
                    this.data.time = data.time
                    this.data.status = data.status
                    this.data.start_time = data.start_time ? format(data.start_time, 'dd/MM/yyyy') : null
                    this.data.end_time = data.end_time ? format(data.end_time, 'dd/MM/yyyy') : null
                    this.data.monitor_ids = data.monitor_ids
                    $('#selectUser').val(data.monitor_ids).change()
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async updatePlanYear() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingPlanCompanyYear(this.data, this.id)
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

        async sentNotificationRegister() {
            this.loading = true
            try {
                const response = await window.apiSentNotificationRegister(this.id)
                if (response.success) {
                    toast.success('Gửi thông báo thành công !')
                    this.data.status = STATUS_SHOPPING_PLAN_COMPANY_REGISTER
                    this.getOrganizationRegisterYear()
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
                    window.location.href = `/shopping-plan-company/year/list`
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
                    window.location.href = `/shopping-plan-company/year/list`
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
                window.location.href = `/shopping-plan-company/year/list`
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getOrganizationRegisterYear() {
            this.loading = true
            try {
                const response = await window.getOrganizationRegisterYear(this.id)
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

        async accountApprovalShoppingPlanOrganization(id, type) {
            this.loading = true
            try {
                const response = await window.apiAccountApprovalShoppingPlanOrganization([id], type)
                if (response.success) {
                    let organization = this.register.organizations.find((item) => +item.id === +id);
                    organization.status = type === ORGANIZATION_TYPE_APPROVAL
                        ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
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
                const response = await window.apiAccountApprovalShoppingPlanOrganization(ids, type)
                if (response.success) {
                    this.register.organizations.filter(function (item) {
                        if (ids.includes(item.id)) {
                            item.status = type === ORGANIZATION_TYPE_APPROVAL
                                ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                        }
                    });
                    this.selectedRow = []
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

        initDateRangePicker() {
            this.dateRangePicker = new AirDatepicker('.dateRange', {
                range: true,
                multipleDatesSeparator: ' - ',
                autoClose: true,
                clearButton: true,
                locale: localeEn,
                dateFormat: 'dd/MM/yyyy',
                onSelect: (selectedDates) => {
                    this.data.start_time = selectedDates.date[0] ? format(selectedDates.date[0], 'dd/MM/yyyy') : null
                    this.data.end_time = selectedDates.date[1] ? format(selectedDates.date[1], 'dd/MM/yyyy') : null
                }
            })
        },

        initYearPicker() {
            new AirDatepicker('.yearPicker', {
                view: 'years', // Hiển thị danh sách năm khi mở
                minView: 'years', // Giới hạn chỉ cho phép chọn năm
                dateFormat: 'yyyy', // Định dạng chỉ hiển thị năm
                autoClose: true, // Tự động đóng sau khi chọn năm
                clearButton: true, // Nút xóa để bỏ chọn
                onSelect: ({date}) => {
                    const year = date.getFullYear();
                    this.data.time = year != null ? year : null
                }
            });
            $('.yearPicker').on('keydown', (e) => {
                if (e.key === 'Backspace' || e.key === 'Delete') {
                    setTimeout(() => {
                        if (!$('.yearPicker').value) {
                            this.data.time = null
                        }
                    }, 0);
                }
            });
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
