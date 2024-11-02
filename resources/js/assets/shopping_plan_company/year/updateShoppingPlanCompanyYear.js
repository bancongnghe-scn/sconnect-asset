import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";

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
                    this.data.time = data.time
                    this.data.status = data.status
                    this.data.start_time = data.start_time
                    this.data.end_time = data.end_time
                    this.data.monitor_ids = data.monitor_ids
                    this.data.organizations = data.organizations
                    this.dateRangePicker.selectDate([window.convertDateString(this.data.start_time), window.convertDateString(this.data.end_time)]);
                    $('#selectUser').val(this.data.monitor_ids).change()
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
                    this.data.start_time = selectedDates.date[0] ?? null
                    this.data.end_time = selectedDates.date[1] ?? null
                }
            })
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'selectUser') {
                    this.data.monitor_ids = value
                }
            });
        },
    }))
})
