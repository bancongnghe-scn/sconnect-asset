import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('updateShoppingPlanCompanyYear', () => ({
        init() {
            this.id = window.location.href.match(/update\/(\d+)/)?.[1];
            this.getInfoShoppingPlanCompanyYear()
            this.getListUser({
                'dept_id' : [
                    ID_ORGANIZATION_NSHC, ID_ORGANIZATION_TCKT
                ]
            })
            this.initDateRangePicker()
            this.initYearPicker()
            this.onChangeSelect2()
        },

        //data
        id: null,
        data: {
            time: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
            organizations: [],
        },
        listUser: [],
        dateRangePicker: null,
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
                    this.data.organizations = data.organizations
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
            const response = await window.apiGetUser(filters)
            if (response.success) {
                this.listUser = response.data.data
            } else {
                toast.error('Lấy danh sách nhân viên thất bại !')
            }
            this.loading = false
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

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                this.data.monitor_ids = $(event.target).val()
            });
        },
    }))
})
