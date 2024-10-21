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
            window.initSelect2Modal(this.idModalUI)
            this.onChangeSelect2()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Kế hoạch',
            register_time: 'Thời gian đăng ký',
            created_by: 'Người tạo',
            created_at: 'Ngày tạo',
            status: 'Trạng thái',
        },
        showAction: {
            view: false,
            edit: true,
            remove: true
        },

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
            status: null,
            type: 'year',
            limit: 10,
            page: 1
        },

        data: {
            time: null,
            type: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },
        listStatus: {
            1: 'Đăng ký',
            2: 'Chờ kế toán duyệt',
            3: 'Chờ giám đốc duyệt'
        },
        listUser: [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalUI: "idModalUI",
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAppendix(this.dataInsert, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.getList(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAppendix(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa hợp đồng thành công !')
            await this.getList(this.filters)

            this.loading = false
        },

        async create() {
            console.log(this.data)
            return
            this.loading = true
            const response = await window.apiCreateAppendix(this.dataInsert)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.loading = false
            this.reloadPage()
            this.resetDataContract()
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.titleModal = 'Thêm mới'
                this.resetData()
            } else {
                this.titleModal = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAppendix(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.dataInsert = this.formatDateAppendix(response.data.data)
            }

            $('#'+this.idModalUI).modal('show');
            this.loading = false
        },

        async handleShowModalInfo(id) {
            this.loading = true
            const response = await window.apiShowContract(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.dataInsert = this.formatDateAppendix(response.data.data)
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

        changePage(page) {
            this.filters.page = page
            this.getListContract(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListContract(this.filters)
        },

        resetData() {
            this.dataInsert = {
                contract_id: null,
                code: null,
                name: null,
                signing_date: null,
                from: 0,
                to: null,
                user_ids: [],
                description: null,
                files: [],
            }
        },

        reloadPage() {
            this.filters = {
                name_code: null,
                contract_ids: [],
                status: [],
                signing_date: null,
                from : null,
                limit: 10,
                page: 1
            }

            this.getList(this.filters)
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
                }
            });
        },

        onChangeYearPicker(el, year) {
            const storageFormat = year != null ? year : null
            console.log(storageFormat)
            console.log(el.id)
            if(el.id === 'filterYear') {
                this.filters.signing_date = storageFormat
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

        onChangeDateRangePicker(el, selectedDates) {
            // const startDate = format(selectedDates.date[0], 'dd/MM/yyyy')
            // const endDate = format(selectedDates.date[1], 'dd/MM/yyyy')
            if (el.id === 'selectDateRegister') {
                console.log(selectedDates)
                this.data.start_time = format(selectedDates.date[0], 'dd/MM/yyyy')
                this.data.end_time = format(selectedDates.date[1], 'dd/MM/yyyy')
            }
        },

        initDateRangePicker() {
            document.querySelectorAll('.dateRange').forEach(el => {
                new AirDatepicker(el, {
                range: true,
                multipleDatesSeparator: ' - ',
                autoClose: true,
                clearButton: true,
                locale: localeEn,
                dateFormat: 'dd/MM/yyyy',
                onSelect: (selectedDates) => {
                    this.onChangeDateRangePicker(el, selectedDates)
                }
            })})
        },
    }));
});
