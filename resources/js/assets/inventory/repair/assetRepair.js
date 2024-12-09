import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format, parse, isValid} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.store('listAssetRepair', {
        instance: null
    })
    Alpine.data('tableAssetRepair', () => ({
        init() {
            window.initSelect2Modal(this.idModalRepair)
            this.list({page: 1, limit: 10})
            Alpine.store('listAssetRepair').instance = this
            this.initDatePicker()
            this.closeModalRepair()
        },

        dataTable: [],
        columns: {
            asset_code: 'Mã tài sản',
            asset_name: 'Tên tài sản',
            asset_reason: 'Mô tả tình trạng hỏng',
            date: 'Ngày hỏng',
            date_repair: 'Ngày sửa chữa',
            status_repair: 'Tình trạng',
        },
        showAction: {
            complete: true,
            cancel: true,
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
            status: null,
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
            1: 'Đang sửa chữa',
            2: 'Hoàn thành sửa chữa',
        },
        id: null,
        idModalRepaired: "idModalRepaired",
        idModalShowRepaired: "idModalShowRepaired",
        idModalRepairMore: "idModalRepairMore",
        numberShow: "numberShow",
        selectedAll: "selectedAll",
        repaired: "repaired",
        dataRepair : [],
        dataShowRepair : [],
        dataModalRepairtMore: [],
        selectedRepairtMore: [],
        saveDraftId: [],            // sử dụng lưu tạm id, trường hợp ấn vào detail và thêm asset repair
        addressRepair: {
            1: 'Tại công ty',
            2: 'Nhà cung cấp'
        },
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
            date_repair: 'Ngày sửa chữa',
            cost_repair: 'Chi phí sửa chữa',
            note_repair: 'Tình trạng sửa chữa',
            address_repair: 'Địa điểm sửa chữa',
            performer_supplier: 'Người thực hiện/Đơn vị sửa chữa',
            address: 'Địa chỉ'
        },
        dataTheadModalRepairtMore: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            date_repair: 'Ngày sửa chữa',
        },

        //methods
        async list(filters){
            this.loading = true

            const response = await window.apiGetListAssetRepair(filters)
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

        async confirmRepaired() {
            this.loading = true
            const date_repaired = $('#'+this.repaired).val();
            const dataUpdate = {
                date_repaired: date_repaired ? format(parse(date_repaired, 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd') : null,
                assets: this.dataRepair
            }

            const response = await window.apiUpdateMultiAssetRepair(dataUpdate)
            if (response.success) {
                this.list(this.filters);
            } else {
                toast.error(response.message)
            }
            this.list(this.filters)
            $('#'+this.idModalRepaired).modal('hide')
            this.selectedRepairtMore = []

            this.loading = false
        },

        // Show edit
        async clickRepaired(id) {
            this.loading = true

            this.saveDraftId.push(id)
            const response = await window.apiGetAssetRepair(id);
            if (response.success) {
                this.dataRepair = response.data.data
            } else {
                toast.error(response.message)
            }
            console.warn('this.dataRepair', this.dataRepair);
            $('#'+this.idModalRepaired).modal('show')

            this.loading = false
        },

        // Only show
        async clickShowRepaired(id) {
            this.loading = true

            const response = await window.apiGetAssetRepair(id);
            if (response.success) {
                this.dataShowRepair = response.data.data[0]
            } else {
                toast.error(response.message)
            }
            
            $('#'+this.idModalShowRepaired).modal('show')

            this.loading = false
        },

        async handleCompleteMultiModalUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )
            const getIds = {
                'asset_repair_ids' : ids
            }
            const response = await window.apiGetMultiAssetRepair(getIds);
            if (response.success) {
                this.dataRepair = response.data.data
            } else {
                toast.error(response.message)
            }
            
            $('#'+this.idModalRepaired).modal('show')

            this.loading = false
        },

        async getAssetRepair() {
            this.loading = true

            // Filter lấy id đang sửa chữa
            const selectedIds = this.dataRepair.map(item => item.id)
            const showAssetRepairIds = this.dataTable.filter(
                i => !selectedIds.includes(i.id) && i.status_repair == 'Đang sửa chữa'
            ).map(i => i.id)

            // Lấy đầy đủ thông tin đưa vào modal mới
            const getIds = {
                'asset_repair_ids' : showAssetRepairIds
            }
            const response = await window.apiGetMultiAssetRepair(getIds);
            if (response.success) {
                this.dataModalRepairtMore = response.data.data
            } else {
                toast.error(response.message)
            }

            $('#'+this.idModalRepairMore).modal('show')
            this.loading = false
        },

        addRepairMore() {
            const repair_ids = Object.keys(this.selectedRepairtMore)
                .filter( key => this.selectedRepairtMore[key] === true)
                .map(Number)
            const select = this.dataModalRepairtMore.filter(i => repair_ids.includes(i.id))

            this.dataRepair = this.dataRepair.concat(select)
            this.selectedRepairtMore = []

            $('#'+this.idModalRepairMore).modal('hide')
        },

        async handleCancelComplete(id) {

            this.dataRepair = this.dataRepair.filter(i => i.id != id)

            if (this.dataRepair.length == 0) {
                $("#"+this.idModalRepaired).modal('hide');
                this.saveDraftId = []
            }
            
        },

        handleAddressRepairChange(index, event) {
            const selectedValue = event.target.value
            this.dataRepair[index].address_repair = selectedValue
        },

        handleSupplierPerformerChange(index, event) {
            const selectedValue = event.target.value
            this.dataRepair[index].performer_supplier = selectedValue
        },

        count() {
            const ids = Object.keys(this.selectedRow).filter(key => 
                this.selectedRow[key] === true && this.selectedRow[key] !== undefined
            );
            
            $('#'+this.numberShow).text(ids.length);
        },

        unselectedAll() {
            this.selectedRow = [];
            $('#'+this.numberShow).text(0);

            if ($('.repair #'+this.selectedAll).is(':checked')) {
                $('.repair #'+this.selectedAll).click();
            }
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
                });

                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {
                                this.onChangeDatePicker(el, null);
                            }
                        }, 0);
                    }
                });
            });
        },

        onChangeDatePicker(el, date) {
            const storageFormat = date != null ? format(date, 'yyyy-MM-dd') : null
            if(el.id === 'repaired') {
                this.data.date_repaired = storageFormat
            }
        },

        closeModalRepair() {
            $('#'+this.idModalRepaired).on('hidden.bs.modal', function () {
                this.selectedRow = []
            });

            $('#'+this.idModalRepairMore).on('hidden.bs.modal', function () {
                this.saveDraftId = []
            });
        }
    }))
})