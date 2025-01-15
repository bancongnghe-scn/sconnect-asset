document.addEventListener('alpine:init', () => {
    Alpine.store('globalData', {
        dataPlanLiquidation: [],
        dataAssetLiquidation: [],
        instance: null
    });
    Alpine.data('tableAssetLiquidation', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.disabledDatePicker()
        },

        dataTable: [],
        columns: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            status: 'Tình trạng',
            date: 'Ngày đề nghị thanh lý',
            reason: 'Lý do',
            price_liquidation: 'Giá thanh lý',
        },
        showAction: {
            create: true,
        },

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        selectedRow: [],

        // Bảng con multi back
        dataPlanLiquidation: [],
        dataColumnsMulti: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            price_liquidation: 'Giá thanh lý',
            reason: 'Lý do',
        },

        //data
        filters: {
            name_code: null,
            limit: 10,
            page: 1
        },
        data: {
            code: null,
            name: null,
            reason: null,
        },
        listStatus: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy',
            4: 'Đã mất',
            5: 'Đã hủy',
            6: 'Đề nghị thanh lý',
            7: 'Đang thanh lý',
            8: 'Đã thanh lý'
        },
        id: null,
        idModalCreatePlan: "idModalCreatePlan",
        numberLiquidation: "numberLiquidation",
        selectedAllLiquidation: "selectedAllLiquidation",
        selectSigningDate: "selectSigningDate",

        assetsProposeLiquidationCount: "assetsProposeLiquidationCount",

        //methods
        async list(filters) {
            this.loading = true

            const response = await window.apiGetAssetLiquidation(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0

                Alpine.store('globalData').dataAssetLiquidation = this.dataTable

                $('#'+this.assetsProposeLiquidationCount).text(`(${data.data.total ?? 0})`)
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

        formatPrice(value) {
            if (!value) return '0';
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },

        formatDate(date) {
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },

        resetData() {
            this.data = {
                code: null,
                name: null,
                reason: null,
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name_code: null,
                limit: 10,
                page: 1
            }
        },

        countLiquidation() {
            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )
            
            $('#'+this.numberLiquidation).text(ids.length);
        },

        unselectedAllLiquidation() {
            this.selectedRow = [];
            $('#'+this.numberLiquidation).text(0);

            if ($('.manage_assets #'+this.selectedAllLiquidation).is(':checked')) {
                $('.manage_assets #'+this.selectedAllLiquidation).click();
            }
        },

        async handleCreatePlanLiquidation() {
            this.loading = true

            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )

            this.dataPlanLiquidation = this.dataTable
            this.dataPlanLiquidation = this.dataPlanLiquidation.filter(item => ids.includes(item.id.toString())).map(item => Object.assign({}, item));
            Alpine.store('globalData').dataPlanLiquidation = this.dataPlanLiquidation;

            $('#'+this.numberLiquidation).text(ids.length);
            $("#"+this.idModalCreatePlan).modal('show');
            this.loading = false
        },

        async handleDeleteOfMultiModalCancelUI(id) {

            this.dataPlanLiquidation = this.dataPlanLiquidation.filter(i => i.id != id)
            Alpine.store('globalData').dataPlanLiquidation = this.dataPlanLiquidation;

            this.dataTable.forEach(
                (item) => {
                    this.selectedRow[id] = false
                }
            )

            if (this.dataPlanLiquidation.length == 0) {
                $("#"+this.idModalCreatePlan).modal('hide');
            }
        },

        disabledDatePicker() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString('en-GB', {
                day: '2-digit', month: '2-digit', year: 'numeric'
            });
            $('#'+this.idModalCreatePlan+' #'+this.selectSigningDate).val(formattedDate);

            // Vô hiệu hóa trường
            $('#'+this.idModalCreatePlan+' #'+this.selectSigningDate).prop('disabled', true);
        },

        async createPlanLiquidation() {
            this.loading = true
            
            const assets_id = { assets_id: this.dataPlanLiquidation.map(item => ({
                id: item.id,
                price_liquidation: item.price_liquidation
            }))};

            
            const response = await window.apiCreatePlanLiquidationFromSelectAsset({
                ...assets_id,
                ...this.data
            })

            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Tạo kế hoạch thanh lý thành công!')
            $('#'+this.idModalCreatePlan).modal('hide')
            this.resetData()
            this.reloadPage()

            // Reset lại bảng plan
            Alpine.store('assetPlanLiquidation').instance.list({page: 1, limit: 10});

            this.loading = false
        }
    }))
})