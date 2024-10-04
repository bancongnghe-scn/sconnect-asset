import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('contract', () => ({
        init() {
            this.initSelect2('modalContractUI');
            this.initSelect2('modalContractInfo');
            this.onChangeSelect2()
            this.initDatePicker()
            this.getListContract({
                page: 1,
                limit: 10
            })
            this.getListSupplier({})
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

        //pagination
        totalPages: null,
        currentPage: 1,
        total: null,
        limit: 10,
        showChecked: false,


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
        contract: {
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
        listUser: [
            {id:1, name: 'User1'},
            {id:2, name: 'User2'},
        ],
        titleModal: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        //methods
        async getListContract(filters) {
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
                this.total = data.data.total
                toast.success('Lấy danh sách hợp đồng thành công !')
            } else {
                toast.error('Lấy danh sách hợp đồng thất bại !')
            }
            this.loading = false
        },

        async editContract() {
            this.loading = true
            const response = await window.apiUpdateContract(this.contract, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập hợp đồng thành công !')
            $('#modalContractUI').modal('hide');
            this.resetDataContract()
            await this.getListContract(this.filters)
            this.loading = false
        },

        async removeContract() {
            this.loading = true
            const response = await window.apiRemoveContract(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa hợp đồng thành công !')
            await this.getListContract(this.filters)

            this.loading = false
        },

        async createContract() {
            this.loading = true
            const response = await window.apiCreateContract(this.contract)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo hợp đồng thành công !')
            $('#modalContractUI').modal('hide');
            this.loading = false
            this.reloadPage()
            this.resetDataContract()
        },

        async handleShowModalContractUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.titleModal = 'Thêm mới'
                this.resetDataContract()
            } else {
                this.titleModal = 'Cập nhật'
                this.id = id
                const response = await window.apiShowContract(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.contract = this.formatDataContract(response.data.data)
            }

            $('#modalContractUI').modal('show');
            this.loading = false
        },

        async handleShowModalContractInfo(id) {
            this.loading = true
            const response = await window.apiShowContract(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.contract = this.formatDataContract(response.data.data)
            $('#modalContractInfo').modal('show');
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

        changePage(page) {
            this.filters.page = page
            this.getListContract(this.filters)
        },

        changeLimit() {
            this.filters.limit = this.limit
            this.getListContract(this.filters)
        },

        resetDataContract() {
            this.contract = {
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
            this.filters = {
                name_code: null,
                type: [],
                status: [],
                signing_date: null,
                from: null,
                limit: 10,
                page: 1
            }

            this.getListContract(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterTypeContract') {
                    this.filters.type = value
                } else if (event.target.id === 'filterStatusContract') {
                    this.filters.status = value
                } else if (event.target.id === 'selectUserId') {
                    this.contract.user_ids = value
                }
            });
        },

        onChangeDatePicker(el, date) {
            const storageFormat = date != null ? format(date, 'dd/MM/yyyy') : null

            if(el.id === 'selectSigningDate') {
                this.contract.signing_date = storageFormat
            } else if (el.id === 'selectFrom') {
                this.contract.from = storageFormat
            } else if (el.id === 'selectTo') {
                this.contract.to = storageFormat
            } else if (el.name === 'selectPaymentDate') {
                this.contract.payments[el.id].payment_date = storageFormat
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

            this.contract.files = this.contract.files.concat(Array.from(this.$refs.fileInput.files))
        },

        addRowPayment() {
            this.contract.payments.push({
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
            const payments = contract.payments
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

        initSelect2(modalId) {
            $(`#${modalId}`).on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $(`#${modalId}`)
                })
            })
        },
    }));
});
