import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyYear', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser()
            this.initYearPicker()
            this.initDateRangePicker()
            window.initSelect2Modal('idModalInsert')
            this.onChangeSelect2()
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
            time: null,
            status: [],
            type: 1,
            limit: 10,
            page: 1
        },

        data: {
            time: null,
            status: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listStatus: {
            1: 'Mới tạo',
            2: 'Đăng ký',
            3: 'Chờ kế toán duyệt',
            4: 'Chờ giám đốc duyệt',
            5: 'Hủy'
        },
        listUser: [],
        dateRangePicker: null,
        permission: [],

        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompany(filters)
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
                this.permission = data.permission
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async edit() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingPlanCompanyYear(this.data, this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Cập nhập kế hoạch năm thành công !')
                $('#idModalUpdate').modal('hide');
                this.resetData()
                this.list(this.filters)
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

        async create() {
            this.loading = true
            try {
                const response = await window.apiCreateShoppingPlanCompanyYear(this.data)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                toast.success('Tạo kế hoạch mua sắm năm thành công !')
                $('#idModalInsert').modal('hide');
                this.resetData()
                this.reloadPage()
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            try {
                this.action = action
                if (action === 'create') {
                    this.resetData()
                    $('#idModalInsert').modal('show');
                } else {
                    this.id = id
                    const response = await window.apiShowShoppingPlanCompany(id)
                    if (!response.success) {
                        toast.error(response.message)
                        return
                    }
                    const data = response.data.data
                    this.data.time = data.time
                    this.data.status = data.status
                    this.data.start_time = data.start_time
                    this.data.end_time = data.end_time
                    this.data.monitor_ids = data.monitor_ids
                    this.dateRangePicker.selectDate([this.convertDateString(this.data.start_time), this.convertDateString(this.data.end_time)]);
                    $('#idModalUpdate').modal('show');
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async handleShowModalInfo(id) {
            this.loading = true
            const response = await window.apiShowContract(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.data = this.formatDateAppendix(response.data.data)
            $('#'+this.idModalInfo).modal('show');
            this.loading = false
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

        async removeMultiple() {
            this.loading = true
            try {
                const response = await window.apiRemoveShoppingPlanCompanyMultiple(this.id)
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
                time: null,
                status: null,
                start_time: null,
                end_time: null,
                monitor_ids: [],
            }
            this.dateRangePicker.clear()
        },

        reloadPage() {
            this.filters = {
                time: null,
                status: [],
                type: 1,
                limit: 10,
                page: 1
            }
            $('#filterStatus').val([]).change()

            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'selectUser') {
                    this.data.monitor_ids = value
                } else if (event.target.id === 'filterStatus') {
                    this.filters.status = value
                }
            });
        },

        onChangeYearPicker(el, year) {
            const storageFormat = year != null ? year : null
            if(el.id === 'filterYear') {
                this.filters.time = storageFormat
            } else if(el.id === 'selectYear') {
                this.data.time = storageFormat
            }
        },

        initYearPicker() {
            document.querySelectorAll('.yearPicker').forEach(el => {
                new AirDatepicker(el, {
                    view: 'years', // Hiển thị danh sách năm khi mở
                    minView: 'years', // Giới hạn chỉ cho phép chọn năm
                    dateFormat: 'yyyy', // Định dạng chỉ hiển thị năm
                    autoClose: true, // Tự động đóng sau khi chọn năm
                    clearButton: true, // Nút xóa để bỏ chọn
                    onSelect: ({date}) => {
                        const year = date.getFullYear();
                        this.onChangeYearPicker(el, year)
                    }
                });
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {
                                this.onChangeYearPicker(el, null)
                            }
                        }, 0);
                    }
                });
            });

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

        convertDateString(dateString) {
            const [year, month, day] = dateString.split('-')
            return new Date(year, month - 1, day)
        },
    }));
});
