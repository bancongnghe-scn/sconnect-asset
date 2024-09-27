import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('contract', () => ({
        init() {
            $('.select2').select2()
            this.onChangeSelect2()
            const datepicker = document.querySelectorAll('.datepicker');
            datepicker.forEach(el => {
                new AirDatepicker(el, {
                    autoClose: true,
                    locale: localeEn,
                    dateFormat: 'dd/MM/yyyy',
                    onSelect: ({date}) => {
                        this.onChangeDatePicker(el, date)
                    }
                });
            });
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên hợp đồng',
            asset_type_group: 'Nhóm loại',
            maintenance_months: 'Thời gian bảo dưỡng(Tháng)',
            measure: 'Đơn vị tính',
            description: 'Ghi chú'
        },
        showAction: {
            view: true,
            edit: true,
            remove: true
        },
        selectedRow: [],
        showChecked: true,
        //pagination
        totalPages: null,
        currentPage: 1,
        total: null,
        limit: 10,

        //data
        filters: {
            name_code: null,
            type: [],
            status: [],
            signing_date: null,
            from: null,
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
            files: [],
            description: null,
        },
        listTypeContract: {
            1: 'Hợp đồng mua bán',
            2: 'Hợp đồng nguyên tắc',
        },
        listStatusContract: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
        },
        listSupplier: [
            {id:1, name: 'NCC1'}
        ],
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

            this.getListContract(this.filters)

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
        },

        async deleteMultiple() {
            this.loading = true
            const ids = Object.keys(this.selectedRow)
            const response = await window.apiDeleteMultipleByIds(ids)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('hide');

            toast.success('Xóa danh sách hợp đồng thành công !')

            this.getListContract(this.filters)

            this.loading = false
        },

        async handleShowModalContractUI(action, id = null) {
            this.action = action
            if (action === 'create') {
                this.titleModal = 'Thêm mới'
            } else {
                this.titleModal = 'Cập nhật'
                this.id = id
                const response = await window.apiShowContract(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                const data = response.data.data
            }

            $('#modalContractUI').modal('show');
        },

        handleContractUI() {
            if (this.action === 'create') {
                this.createContract()
            } else {
                this.editContract()
            }
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
            this.contract.name = null
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

        confirmDeleteMultiple() {
            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
        },

        searchContract() {
            this.filters.asset_type_group_id = $('select[name="asset_type_group"]').val()
            this.getListContract(this.filters)
        },

        onChangeSelect2() {
            $('#modalContractUI').on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $('#modalContractUI') // Gán dropdown vào modal
                });
            });

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
            const storageFormat = format(date, 'yyyy-MM-dd');

            if(el.id === 'selectSigningDate') {
                this.contract.signing_date = storageFormat
            } else if (el.id === 'selectFrom') {
                this.contract.from = storageFormat
            } else if (el.id === 'selectTo') {
                this.contract.to = storageFormat
            }
        }
    }));
});
