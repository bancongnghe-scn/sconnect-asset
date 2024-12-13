import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format, parse, isValid} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.store('globalData', {
        dataRepair: [],
    });
    Alpine.data('tableAssetDamaged', () => ({
        init() {
            window.initSelect2Modal(this.idModalRepair)
            this.onChangeSelect2()
            this.list({page: 1, limit: 10})
            this.initDatePicker()
            this.closeModalRepair()
        },

        dataTable: [],
        columns: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            status: 'Tình trạng',
            date: 'Ngày hỏng',
            reason: 'Mô tả tình trạng hỏng',
            user_name: 'Nhân viên sử dụng',
            location: 'Vị trí tài sản',
        },
        showAction: {
            repaid: true,
            liquidation: true,
            cancel: true,
            remove: true,
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
            location: null,
            date_repair: null,
            date_repaired: null,
            address: null
        },
        listStatus: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy',
            4: 'Đã mất',
            5: 'Đã hủy'
        },
        id: null,
        idModalRepair: "idModalRepair",
        idModalDamagedMore: "idModalDamagedMore",
        idModalLiquidationMore: "idModalLiquidationMore",
        idModalCancelMore: "idModalCancelMore",
        dataRepair : [],
        performer: {
            1: 'Minh Hoàng',
            2: 'Long sky',
            3: 'Trường con',
            4: 'Hiếu 9 ngón'
        },
        supplier: {
            1: 'Sconnect studio',
            2: 'Sconnect media',
            3: 'Sconnect academy'
        },

        columnsRepair: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            price: 'Giá trị',
            date: 'Ngày hỏng',
            repair_cost: 'Chi phí sửa chữa',
            reason: 'Tình trạng sửa chữa',
        },
        idModalDetailCost: "idModalDetailCost",
        dataTheadModalDamagedMore : {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            date: 'Ngày hỏng',
        },
        dataModalDamagedMore: [],
        selectedDamagedMore: [],

        // Thanh lý
        idModalLiquidation: "idModalLiquidation",
        dataLiquidation: [],
        dataColumnsLiquidation: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            price_liquidation: 'Giá trị thanh lý'
        },
        dateLiquidation: "dateLiquidation",
        reasonLiquidation: "reasonLiquidation",
        selectedLiquidationMore: [],
        dataModalLiquidationMore: [],

        // Hủy
        idModalCancel: "idModalCancel",
        idModalCancelMore: "idModalCancelMore",
        dataCancel: [],
        dataColumnsCancel: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            user_name: 'Nhân viên sử dụng',
            location: 'Vị trí tài sản'
        },
        dateCancel: "dateCancel",
        reasonCancel: "reasonCancel",
        selectedCancelMore: [],
        dataModalCancelMore: [],

        //methods
        async list(filters){
            this.loading = true

            const response = await window.apiGetAssetDamaged(filters)
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

        handleRepaidModalUI(id) {
            this.loading = true

            this.dataRepair = this.dataTable.filter(item => item.id == id)
            $('#'+this.idModalRepair).modal('show')

            this.loading = false
        },

        async confirmRepair() {
            this.loading = true

            this.data.location = this.location
            this.data.address = $('#address').val()
            this.data.supplier_id = $('#idSupplier').val()
            this.data.performer_id = $('#idPerformer').val()
            this.data.date_repair = $('#dateRepair').val() ? format(parse($('#dateRepair').val(), 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd') : null
            this.data.date_repaired = $('#dateRepaired').val() ? format(parse($('#dateRepaired').val(), 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd') : null

            const dataUpdate = {
                ...this.data,
                assets: this.dataRepair
            }

            const response = await window.apiUpdateAssetRepair(dataUpdate)
            if (response.success) {
                this.list(this.filters)
            } else {
                toast.error(response.message)
            }
            $('#'+this.idModalRepair).modal('hide')

            // Reset lại bảng repair
            Alpine.store('listAssetRepair').instance.list({page: 1, limit: 10})

            this.loading = false
        },

        modalRepairMultiUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow)
                .filter( key => this.selectedRow[key] === true )
                .map(Number)

            this.dataRepair = this.dataTable.filter(item => ids.includes(item.id))
            $('#'+this.idModalRepair).modal('show')

            this.loading = false
        },

        async getAssetDamagedModal(type) {
            
            const response = await window.apiGetAssetDamaged({
                name_code: null
            })
            
            if (type == 'repair') {
                const assetsDamaged = response.data.data
                const ids = this.dataRepair.map(i => i.id)
                this.dataModalDamagedMore = assetsDamaged.filter(i => !ids.includes(i.id))
                $('#'+this.idModalDamagedMore).modal('show')
            }

            if (type == 'liquidation') {
                const assetsLiquidation = response.data.data
                const ids = this.dataLiquidation.map(i => i.id)
                this.dataModalLiquidationMore = assetsLiquidation.filter(i => !ids.includes(i.id))
                $('#'+this.idModalLiquidationMore).modal('show')
            }

            if (type == 'cancel') {
                const assetsCancel = response.data.data
                const ids = this.dataCancel.map(i => i.id)
                this.dataModalCancelMore = assetsCancel.filter(i => !ids.includes(i.id))
                $('#'+this.idModalCancelMore).modal('show')
            }
        },

        addRepairMore() {
            const ids = Object.keys(this.selectedDamagedMore)
                .filter( key => this.selectedDamagedMore[key] === true )
                .map(Number)
            
            const select = this.dataModalDamagedMore.filter(i => ids.includes(i.id))
            this.dataRepair = this.dataRepair.concat(select)

            this.selectedDamagedMore = []
            $('#'+this.idModalDamagedMore).modal('hide')
        },

        handleCancelOfModalRepairUI(id) {
            this.dataRepair = this.dataRepair.filter(i => i.id != id)
        },

        countAssetDamaged() {
            const ids = Object.keys(this.selectedRow).filter(key => 
                this.selectedRow[key] === true && this.selectedRow[key] !== undefined
            )
            
            // $('#'+this.numberShow).text(ids.length)
        },

        initDatePicker() {
            document.querySelectorAll('.datepicker').forEach(el => {
                new AirDatepicker(el, {
                    autoClose: true,
                    clearButton: true,
                    locale: localeEn,
                    dateFormat: 'dd/MM/yyyy',
                    onSelect: ({date}) => {
                        this.onChangeDatePicker(el, date)
                    }
                })

                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {
                                this.onChangeDatePicker(el, null)
                            }
                        }, 0)
                    }
                })
            })
        },

        onChangeDatePicker(el, date) {
            const storageFormat = date != null ? format(date, 'yyyy-MM-dd') : null
            if(el.id === 'dateRepair') {
                this.data.date_repair = storageFormat
            } else if(el.id === 'dateRepaired') {
                this.data.date_repaired = storageFormat
            }
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const val = $(event.target).val()

                if (event.target.id === 'idPerformer') {
                    this.data.select_performer = val
                } else if (event.target.id === 'idSupplier') {
                    this.data.select_supplier = val
                }
            })
        },

        closeModalRepair() {
            $('#'+this.idModalRepair).on('hidden.bs.modal', function () {
                this.selectedRow = []
            })

            $('#'+this.idModalDamagedMore).on('hidden.bs.modal', function () {
                this.selectedDamagedMore = []
            })

            $('#'+this.idModalLiquidation).on('hidden.bs.modal', function () {
                this.selectedRow = []
            })

            $('#'+this.idModalLiquidationMore).on('hidden.bs.modal', function () {
                this.selectedDamagedMore = []
            })
        },

        // Thanh lý
        handleLiquidationModalUI(id) {
            this.loading = true

            this.dataLiquidation = this.dataTable.filter(item => item.id == id)
            $('#'+this.idModalLiquidation).modal('show')

            this.loading = false
        },

        modalLiquidationMultiUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow)
                .filter( key => this.selectedRow[key] === true )
                .map(Number)

            this.dataLiquidation = this.dataTable.filter(item => ids.includes(item.id))
            $('#'+this.idModalLiquidation).modal('show')

            this.loading = false
        },

        async completeLiquidation() {
            const dateLiquidation = $('#'+this.dateLiquidation).val()
            const reasonLiquidation = $('#'+this.reasonLiquidation).val()
            const assetsLiquidation = this.dataLiquidation.map(item => ({
                id: item.id,
                price_liquidation: item.price_liquidation,
                date: dateLiquidation ? format(parse(dateLiquidation, 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd') : null,
                reason: reasonLiquidation
            }))

            const dataUpdateLiquidation = {
                asset_liqui: assetsLiquidation
            }

            const response = await window.apiUpdateAssetLiquidation(dataUpdateLiquidation)
            if (response.success) {
                this.list(this.filters)
            } else {
                toast.error(response.message)
            }

            $('#'+this.idModalLiquidation).modal('hide')
        },

        addLiquidatonMore() {
            const ids = Object.keys(this.selectedLiquidationMore)
                .filter( key => this.selectedLiquidationMore[key] === true )
                .map(Number)
            
            const select = this.dataModalLiquidationMore.filter(i => ids.includes(i.id))
            this.dataLiquidation = this.dataLiquidation.concat(select)

            this.selectedLiquidationMore = []
            $('#'+this.idModalLiquidationMore).modal('hide')
        },

        handleRemoveLiquidationOfModalUI(id) {
            this.dataLiquidation = this.dataLiquidation.filter(i => i.id != id)
        },

        // Hủy
        handleCancelModalUI(id) {
            this.loading = true

            this.dataCancel = this.dataTable.filter(item => item.id == id)
            $('#'+this.idModalCancel).modal('show')

            this.loading = false
        },

        modalCancelMultiUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow)
                .filter( key => this.selectedRow[key] === true )
                .map(Number)

            this.dataCancel = this.dataTable.filter(item => ids.includes(item.id))
            $('#'+this.idModalCancel).modal('show')

            this.loading = false
        },

        addCancelMore() {
            const ids = Object.keys(this.selectedCancelMore)
                .filter( key => this.selectedCancelMore[key] === true )
                .map(Number)
            
            const select = this.dataModalCancelMore.filter(i => ids.includes(i.id))
            this.dataCancel = this.dataCancel.concat(select)

            this.selectedCancelMore = []
            $('#'+this.idModalCancelMore).modal('hide')
        },

        handleRemoveCancelOfModalUI(id) {
            this.dataCancel = this.dataCancel.filter(i => i.id != id)
        },

        async completeCancel() {
            const dateCancel = $('#'+this.dateCancel).val()
            const reasonCancel = $('#'+this.reasonCancel).val()
            const assetsCancel = this.dataCancel.map(item => ({
                id: item.id,
                date: dateCancel ? format(parse(dateCancel, 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd') : null,
                reason: reasonCancel
            }))

            const dataUpdateCancel = {
                asset_cancel: assetsCancel
            }

            const response = await window.apiUpdateAssetCancel(dataUpdateCancel)
            if (response.success) {
                this.list(this.filters)
            } else {
                toast.error(response.message)
            }

            $('#'+this.idModalCancel).modal('hide')
        }
    }))
})