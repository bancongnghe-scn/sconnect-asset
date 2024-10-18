import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('contract', () => ({
        init() {
            window.initSelect2Modal(this.idModalUI);
            window.initSelect2Modal(this.idModalInfo);
            this.onChangeSelect2()
            this.initDatePicker()
            this.list({page: 1, limit: 10})
            this.getListSupplier({})
            this.getListUser({page: 1, limit:20})
        },

        //dataTable
        dataTable: [],
        columns: {
            code: 'Mã hợp đồng',
            type: 'Loại hợp đồng',
            name: 'Tên hợp đồng',
            supplier_name: 'Tên nhà cung cấp',
            signing_date: 'Ngày ký',
            contract_value: 'Tổng giá trị',
            validity: 'Hiệu lực',
            status: 'Trạng thái',
        },
        showAction: {
            view: true,
            edit: true,
            remove: true
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
            name_code: null,
            type: [],
            status: [],
            signing_date: null,
            from : null,
            limit: 10,
            page: 1
        },
        data: {
            code: null,
            type: null,
            name: null,
            supplier_id: null,
            signing_date: null,
            from: null,
            to: null,
            user_ids: [],
            contract_value: null,
            description: null,
            files: [],
            payments: []
        },
        listTypeContract: {
            1: 'Hợp đồng mua bán',
            2: 'Hợp đồng nguyên tắc',
        },
        listStatusContract: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy'
        },
        listSupplier: [],
        listUser: [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "idModalUI",
        idModalInfo: "idModalInfo",

        //methods
        async list(filters) {
            this.loading = true
            if (filters.signing_date) {
                filters.signing_date = format(filters.signing_date, 'yyyy-MM-dd')
            }
            if (filters.from) {
                filters.from = format(filters.from, 'yyyy-MM-dd')
            }
            const response = await window.apiGetContract(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0
            } else {
                toast.error('Lấy danh sách hợp đồng thất bại !')
            }
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateContract(this.data, this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }

            toast.success('Cập nhập hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false

        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveContract(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            await this.list(this.filters)
            toast.success('Xóa hợp đồng thành công !')
            this.loading = false
        },

        async removeMultiple() {
            this.loading = true
            const response = await window.apiRemoveContractMultiple(this.id)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            $("#"+this.idModalConfirmDeleteMultiple).modal('hide')
            await this.list(this.filters)
            this.selectedRow = []
            toast.success('Xóa danh sách hợp đồng thành công !')
            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateContract(this.data)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            this.reloadPage()
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

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.title = 'Thêm mới'
                this.resetData()
            } else {
                this.title = 'Cập nhật'
                this.id = id
                const response = await window.apiShowContract(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = this.formatDataContract(response.data.data)
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
            this.data = this.formatDataContract(response.data.data)
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
                code: null,
                type: null,
                name: null,
                supplier_id: null,
                signing_date: null,
                from: null,
                to: null,
                user_ids: [],
                contract_value: null,
                description: null,
                files: [],
                payments: [],
            }
        },

        reloadPage() {
            this.resetFilters()
            this.list(this.filters)
        },

        resetFilters() {
            this.filters = {
                name_code: null,
                type: [],
                status: [],
                signing_date: null,
                from : null,
                limit: 10,
                page: 1
            }
            $('#filterTypeContract').val([]).change()
            $('#filterStatusContract').val([]).change()
            $('#filterSigningDate').val(null).change()
            $('#filterFrom').val(null).change()
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn ngành hàng cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterTypeContract') {
                    this.filters.type = value
                } else if (event.target.id === 'filterStatusContract') {
                    this.filters.status = value
                } else if (event.target.id === 'selectUserId') {
                    this.data.user_ids = value
                } else if (event.target.id === 'selectSupplier') {
                    this.data.supplier_id = value
                } else if (event.target.id === 'selectContractType') {
                    this.data.type = value
                }
            });
        },

        onChangeDatePicker(el, date) {
            const storageFormat = date != null ? format(date, 'dd/MM/yyyy') : null
            if(el.id === 'selectSigningDate') {
                this.data.signing_date = storageFormat
            } else if (el.id === 'selectFrom') {
                this.data.from = storageFormat
            } else if (el.id === 'selectTo') {
                this.data.to = storageFormat
            } else if (el.name === 'selectPaymentDate') {
                this.data.payments[el.id].payment_date = storageFormat
            } else if (el.id === 'filterSigningDate') {
                this.filters.signing_date = storageFormat
            } else if (el.id === 'filterFrom') {
                this.filters.from = storageFormat
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

            this.data.files = this.data.files.concat(Array.from(this.$refs.fileInput.files))
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

        formatDataContract(contract) {
            contract.signing_date = contract.signing_date !== null ? format(contract.signing_date, 'dd/MM/yyyy') : null
            contract.from = contract.from !== null ? format(contract.from, 'dd/MM/yyyy') : null
            contract.to = contract.to !== null ? format(contract.to, 'dd/MM/yyyy') : null
            contract.files = contract.files ?? []
            const payments = contract.payments ?? []
            payments.map((payment) => payment.payment_date = format(payment.payment_date, 'dd/MM/yyyy'))
            return contract
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
