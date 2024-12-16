import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format, parse, isValid} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.store('globalData', {
        dataSelectMulti: [],
        instance: null
    });
    Alpine.data('tableAssetLost', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.initDatePicker()
        },

        dataTable: [],
        columns: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            user_name: 'Nhân viên sử dụng',
            status: 'Tình trạng',
            date: 'Ngày mất',
            location: 'Vị trí tài sản',
            reason: 'Lý do mất',
        },
        showAction: {
            edit: true,
            cancel: true,
            delete: true,
            back: true,
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
        dataSelectMulti: [],
        dataColumnsMulti: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            user_name: 'Nhân viên sử dụng',
            location: 'Vị trí tài sản',
            reason: 'Ghi chú',
        },

        // Bảng con multi cancel
        dataColumnsMultiCancel: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            user_name: 'Nhân viên sử dụng',
            location: 'Vị trí tài sản',
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
            employee: null,
            status: null,
            date: null,
            location: null,
            reason: null,
            signing_date: null
        },
        listStatus: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy',
            4: 'Đã mất',
            5: 'Đã hủy'
        },

        assets: {
            'Hoạt động' : 1,
            'Đã hủy' : 5,
        },

        id: null,
        idModalBackUI: "idModalBackUI",
        idModalCancelUI: "idModalCancelUI",
        idModalBackMultiple: "idModalBackMultiple",
        idModalCancelMultiple: "idModalCancelMultiple",

        numbAssetLost: "numbAssetLost",
        numbAssetCancel: "numbAssetCancel",

        numberShow: "numberShow",
        selectedAll: "selectedAll",
        selectSigningDate: "selectSigningDate",
        reasonCancel: "reasonCancel",

        //methods
        async list(filters) {
            this.loading = true

            const response = await window.apiGetAssetLost(filters)
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

        async handleBackModalUI(id) {
            this.loading = true
            this.id = id
            const response = await window.apiShowAssetLost(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.data = response.data.data

            $('#filterSigningDate').val(null).change()
            $('#'+this.idModalBackUI).modal('show');
            this.loading = false
        },

        async handleCancelModalUI(id) {
            this.loading = true
            this.id = id
            const response = await window.apiShowAssetLost(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.data = response.data.data

            $('#filterSigningDate').val(null).change()
            $('#'+this.idModalCancelUI).modal('show');

            // Reset lại bảng cancel
            Alpine.store('assetCancelStore').instance.list({page: 1, limit: 10});
            this.loading = false
        },

        async handleBackMultiModalUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )

            this.dataSelectMulti = this.dataTable
            this.dataSelectMulti = this.dataSelectMulti.filter(item => ids.includes(item.id.toString())).map(item => Object.assign({}, item));
            Alpine.store('globalData').dataSelectMulti = this.dataSelectMulti;

            $('#'+this.numbAssetLost).text(ids.length);
            $("#"+this.idModalBackMultiple).modal('show');
            this.loading = false
        },

        async handleCancelMultiModalUI() {
            this.loading = true

            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )
            this.dataSelectMulti = this.dataTable
            this.dataSelectMulti = this.dataSelectMulti.filter(item => ids.includes(item.id.toString())).map(item => Object.assign({}, item));
            Alpine.store('globalData').dataSelectMulti = this.dataSelectMulti;

            $('#'+this.numbAssetCancel).text(ids.length);
            $("#"+this.idModalCancelMultiple).modal("show");
            this.loading = false
        },

        async handleDeleteOfMultiModalBackUI(id) {

            this.dataSelectMulti = this.dataSelectMulti.filter(i => i.id != id)
            Alpine.store('globalData').dataSelectMulti = this.dataSelectMulti;

            this.dataTable.forEach(
                (item) => {
                    this.selectedRow[id] = false
                }
            )

            $('#'+this.numbAssetLost).text(this.dataSelectMulti.length);
            if (this.dataSelectMulti.length == 0) {
                $("#"+this.idModalBackMultiple).modal('hide');
            }
        },

        async handleDeleteOfMultiModalCancelUI(id) {

            this.dataSelectMulti = this.dataSelectMulti.filter(i => i.id != id)
            Alpine.store('globalData').dataSelectMulti = this.dataSelectMulti;

            this.dataTable.forEach(
                (item) => {
                    this.selectedRow[id] = false
                }
            )

            $('#'+this.numbAssetCancel).text(this.dataSelectMulti.length);
            if (this.dataSelectMulti.length == 0) {
                $("#"+this.idModalCancelMultiple).modal('hide');
            }
        },

        async confirmBackMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn ngành hàng cần xóa !')
                return
            }


            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách hợp đồng thành công !')
            this.loading = false
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
            const storageFormat = date != null ? format(date, 'dd/MM/yyyy') : null
            if(el.id === 'filterSigningDate') {
                this.filters.signing_date = storageFormat
            } else if(el.id === 'filterFrom') {
                this.filters.from = storageFormat
            } else if(el.id === 'selectSigningDate') {
                this.data.signing_date = storageFormat
            } else if(el.id === 'selectFrom') {
                this.data.from = storageFormat
            } else if(el.id === 'selectTo') {
                this.data.to = storageFormat
            }
        },

        // Back trạng thái mất
        async revert() {
            this.loading = true

            // Chuyển về trạng thái hoạt động
            this.data.status = this.assets['Hoạt động'];
            let signing_date = $('#'+this.idModalBackUI+' #'+this.selectSigningDate).val()
            
            if (signing_date != null) {
                const formattedDate = format(parse(signing_date, 'dd/MM/yyyy', new Date()), 'y-M-d');
                this.data.signing_date = formattedDate;
            }

            const statusUpdate = {
                update_status_assets : [this.data]
            }

            const response = await window.apiRevertAsset(statusUpdate)
            
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập tài sản thành công !')
            $('#'+this.idModalBackUI).modal('hide');
            this.resetData()
            await this.list(this.filters)

            this.loading = false
        },

        async revertMulti() {
            this.loading = true

            let signing_date = $('#'+this.idModalBackMultiple+' #'+this.selectSigningDate).val()
            if (signing_date != null) {
                const formattedDate = format(parse(signing_date, 'dd/MM/yyyy', new Date()), 'y-M-d');
                this.dataSelectMulti.forEach(item => {
                    item.signing_date = formattedDate;
                });
            }

            // Revert assets về status = 1
            this.dataSelectMulti.forEach(item => {
                item.status = this.assets['Hoạt động'];
            });

            const statusUpdate = {
                update_status_assets : this.dataSelectMulti
            }

            const response = await window.apiRevertAsset(statusUpdate)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập tài sản thành công !')
            $("#"+this.idModalBackMultiple).modal('hide');
            this.resetData()
            await this.list(this.filters)

            this.loading = false
        },


        // Chuyển trạng thái hủy
        async cancel() {
            this.loading = true

            // Trạng thái hủy = 5
            this.data.status = this.assets['Đã hủy'];
            let signing_date = $('#'+this.idModalCancelUI+' #'+this.selectSigningDate).val()
            
            if (signing_date != null) {
                const formattedDate = format(parse(signing_date, 'dd/MM/yyyy', new Date()), 'y-M-d');
                this.data.signing_date = formattedDate;
            }

            const statusUpdate = {
                update_status_assets : [this.data]
            }

            const response = await window.apiCanceltAsset(statusUpdate)
            
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập tài sản thành công !')
            $('#'+this.idModalCancelUI).modal('hide');
            this.resetData()
            await this.list(this.filters)

            this.loading = false
        },

        async cancelMulti() {
            this.loading = true

            const signing_date = $('#'+this.idModalCancelMultiple+' #'+this.selectSigningDate).val()
            const description = $('#'+this.idModalCancelMultiple+' #'+this.reasonCancel).val()

            if (signing_date != null) {
                const formattedDate = format(parse(signing_date, 'dd/MM/yyyy', new Date()), 'y-M-d');
                this.dataSelectMulti.forEach(item => {
                    item.signing_date = formattedDate;
                });
            }

            // Cancel assets về status = 5
            this.dataSelectMulti.forEach(item => {
                item.status = this.assets['Đã hủy'];
                item.description = description
            });

            const statusUpdate = {
                update_status_assets : this.dataSelectMulti
            }

            const response = await window.apiRevertAsset(statusUpdate)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập tài sản thành công !')
            $("#"+this.idModalCancelMultiple).modal('hide');
            this.resetData()
            await this.list(this.filters)

            Alpine.store('assetCancelStore').instance.list({page: 1, limit: 10});
            this.loading = false
        },

        resetData() {
            this.data = {
                code: null,
                name: null,
                employee: null,
                status: null,
                lost_date: null,
                assets_location: null,
                reason: null,
                signing_date: null
            }
        },

        count() {
            const ids = Object.keys(this.selectedRow).filter( key => this.selectedRow[key] === true )
            
            $('#'+this.numberShow).text(ids.length);
        },

        unselectedAll() {
            this.selectedRow = [];
            $('#'+this.numberShow).text(0);

            if ($('.manage_assets #'+this.selectedAll).is(':checked')) {
                $('.manage_assets #'+this.selectedAll).click();
            }
        }
    }))
})