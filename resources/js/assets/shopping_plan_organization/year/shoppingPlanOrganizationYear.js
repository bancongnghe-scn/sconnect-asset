import AirDatepicker from "air-datepicker";
document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationYear', () => ({
        init() {
            this.list({page:1, limit:10})
            this.initYearPicker()
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
            limit: 10,
            page: 1
        },

        listStatus: STATUS_SHOPPING_PLAN_ORGANIZATION,
        action: null,
        id: null,
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanOrganizationYear(filters)
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

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },

        reloadPage() {
            this.filters = {
                time: null,
                status: [],
                type: 1,
                limit: 10,
                page: 1
            }
            $('#filterStatusOrganization').val([]).change()

            this.list(this.filters)
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                this.filters.status = $(event.target).val()
            });
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
                        this.filters.time = year != null ? year : null
                    }
                });
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {
                                this.filters.time = null
                            }
                        }, 0);
                    }
                });
            });

        },
    }));
});
