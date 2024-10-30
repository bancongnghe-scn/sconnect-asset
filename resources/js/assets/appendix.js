import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('appendix', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.getListContract({status: [2]})
            this.getListUser({page: 1, limit:20})
            this.initDatePicker()
            window.initSelect2Modal(this.idModalUI);
            window.initSelect2Modal(this.idModalInfo);
            this.onChangeSelect2()
        },

        //dataTable
        dataTable: [],
        columns: {
            code: 'Mã phụ lục',
            name: 'Tên phụ lục',
            contract_name: 'Tên hợp đồng',
            contract_code: 'Mã hợp đồng',
            signing_date: 'Ngày ký',
            status: 'Trạng thái',
            validity: 'Hiệu lực',
        },
        showAction: {
            view: true,
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
        selectedRow: [],

        //data
        filters: {
            name_code: null,
            contract_ids: [],
            status: [],
            signing_date: null,
            from : null,
            limit: 10,
            page: 1
        },
        data: {
            contract_id: null,
            code: null,
            name: null,
            signing_date: null,
            from: null,
            to: null,
            description: null,
            link: null,
            user_ids: [],
            files: [],
        },
        listContract: [],
        listStatus: statusAppendix,
        listUser: [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "idModalUIAppendix",
        idModalInfo: "idModalInfo",

        //methods
        async list(filters){
            this.loading = true
            const response = await window.apiGetAppendix(filters)
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

        async create() {
            this.loading = true
            const response = await window.apiCreateAppendix(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAppendix(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAppendix(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa phụ lục hợp đồng thành công !')
            this.loading = false
        },

        async removeMultiple() {
            this.loading = true
            const response = await window.apiRemoveAppendixMultiple(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách phụ lục hợp đồng thành công !')
            this.loading = false
        },

        async getListContract(filters) {
            this.loading = true
            const response = await window.apiGetContract(filters)
            if (response.success) {
                this.listContract = response.data.data
            } else {
                toast.error('Lấy danh sách hợp đồng thất bại !')
            }
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

        async getListSupplier(filters) {
            this.loading = true
            const response = await window.apiGetSupplier(filters)
            if (response.success) {
                this.listSupplier = response.data.data.data
            } else {
                toast.error('Lấy danh sách nhà cung cấp thất bại !')
            }
            this.loading = false
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAppendix(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = this.formatDateAppendix(response.data.data)
            }

            $('#'+this.idModalUI).modal('show');
            this.loading = false
        },

        async handleShowModalInfo(id) {
            this.loading = true
            const response = await window.apiShowAppendix(id)
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

        resetData() {
            this.data = {
                contract_id: null,
                code: null,
                name: null,
                signing_date: null,
                from: null,
                to: null,
                user_ids: [],
                description: null,
                files: [],
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name_code: null,
                contract_ids: [],
                status: [],
                signing_date: null,
                from : null,
                limit: 10,
                page: 1
            }
            $('#filterContract').val([]).change()
            $('#filterStatusAppendix').val([]).change()
            $('#filterSigningDate').val(null).change()
            $('#filterFrom').val(null).change()
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn phụ lục hợp đồng cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterContract') {
                    this.filters.contract_ids = value
                } else if (event.target.id === 'filterStatusAppendix') {
                    this.filters.status = value
                } else if (event.target.id === 'selectUserId') {
                    this.data.user_ids = value
                } else if (event.target.id === 'selectContract') {
                    this.data.contract_id = value
                }
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

        handleFiles() {
            const files = Array.from(this.$refs.fileInput.files)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > maxSize) {
                    toast.error("File " + files[i].name + " vượt quá kích thước tối đa 5MB.")
                    return;
                }
            }

            this.data.files = this.data.files.concat(files)
        },

        addRowPayment() {
            this.data.payments.push({
                payment_date: null,
                money: null,
                description: null
            })

            this.$nextTick(() => {
                this.initDatePicker()
            });
        },

        formatDateAppendix(appendix) {
            appendix.signing_date = appendix.signing_date !== null ? format(appendix.signing_date, 'dd/MM/yyyy') : null
            appendix.from = appendix.from !== null ? format(appendix.from, 'dd/MM/yyyy') : null
            appendix.to = appendix.to !== null ? format(appendix.to, 'dd/MM/yyyy') : null
            return appendix
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
    }));
});
