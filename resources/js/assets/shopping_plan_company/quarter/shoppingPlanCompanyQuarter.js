document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanCompanyQuarter', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListUser({ 'dept_id' : DEPT_IDS_FOLLOWERS })
            this.getListPlanCompanyYear()
            window.initSelect2Modal('idModalInsert')
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
            plan_year_id: null,
            time: null,
            status: [],
            limit: 10,
            page: 1
        },
        data: {
            plan_year_id: null,
            time: null,
            start_time: null,
            end_time: null,
            monitor_ids: [],
        },

        listStatus: STATUS_SHOPPING_PLAN_COMPANY,
        listUser: [],
        listPlanCompanyYear: [],
        listQuarter: LIST_QUARTER,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanCompanyQuarter(filters)
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
                const response = await window.apiCreateShoppingPlanCompanyQuarter(this.data)
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
                const response = await window.apiRemoveShoppingPlanCompanyMultiple(this.id, TYPE_SHOPPING_PLAN_COMPANY_QUARTER)
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

        async getListPlanCompanyYear(){
            this.loading = true
            const response = await window.apiGetShoppingPlanCompany({type: TYPE_SHOPPING_PLAN_COMPANY_YEAR})
            if (response.success) {
                this.listPlanCompanyYear = response.data
            } else {
                toast.error('Lấy danh sách kế hoạch năm !')
            }
            this.loading = false
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
                start_time: null,
                end_time: null,
                monitor_ids: [],
            }
        },

        reloadPage() {
            this.filters = {
                plan_year_id: null,
                time: null,
                status: [],
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