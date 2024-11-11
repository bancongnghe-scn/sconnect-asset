document.addEventListener('alpine:init', () => {
    Alpine.store('assetCancelStore', {
        instance: null
    });
    Alpine.data('tableAssetCancel', () => ({
        init() {
            this.list({page: 1, limit: 10}),
            Alpine.store('assetCancelStore').instance = this;
        },

        dataTable: [],
        columns: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            user_name: 'Nhân viên sử dụng',
            status: 'Tình trạng',
            cancel_date: 'Ngày hủy',
            assets_location: 'Vị trí tài sản',
            cancel_reason: 'Lý do hủy',
        },
        showAction: {
        },

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        selectedRow: [],

        //data
        filters: {
            name_code: null,
            limit: 10,
            page: 1
        },
        data: {
            code: null,
            name: null,
            employee: null,
            status: null,
            lost_date: null,
            assets_location: null,
            reason: null,
        },
        listStatus: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy',
            4: 'Đã mất',
            5: 'Đã hủy'
        },

        //methods
        async list(filters){
            this.loading = true

            const response = await window.apiGetAssetCancel(filters)
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

        changePage(page) {
            this.filters.page = page
            this.list(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.list(this.filters)
        },
    }))
})