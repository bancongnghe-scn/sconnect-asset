document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlanOrganizationWeek', () => ({
        init() {
            this.list({page:1, limit:10})
            this.getListPlanCompanyQuarter()
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

        listPlanCompanyQuarter: [],
        action: null,
        id: null,
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            try {
                const response = await window.apiGetShoppingPlanOrganizationWeek(filters)
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
                limit: 10,
                page: 1
            }
            this.list(this.filters)
        },
    }));
});
