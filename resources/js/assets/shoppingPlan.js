import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('shoppingPlan', () => ({
        init() {
            this.initYearpicker()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Kế hoạch',
            created_by: 'Người tạo',
            created_at: 'Ngày tạo',
            status: 'Trạng thái',
        },
        showAction: {
            view: false,
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
            contract_ids: [],
            status: [],
            signing_date: null,
            from : null,
            limit: 10,
            page: 1
        },
        dataInsert: {
            contract_id: null,
            code: null,
            name: null,
            signing_date: null,
            from: null,
            to: null,
            user_ids: [],
            description: null,
            files: [],
        },
        listContract: [],
        listStatus: {
            1: 'Chờ duyệt',
            2: 'Đã duyệt',
            3: 'Hủy'
        },
        listUser: [
            {id:1, name: 'User1'},
            {id:2, name: 'User2'},
        ],
        titleModal: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalUI: "idModalUI",
        idModalInfo: "idModalInfo",

        //methods
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

        async getList(filters){
            this.loading = true
            const response = await window.apiGetAppendix(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        async edit() {
            this.loading = true
            const response = await window.apiUpdateAppendix(this.dataInsert, this.id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            toast.success('Cập nhập phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.getList(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveAppendix(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa hợp đồng thành công !')
            await this.getList(this.filters)

            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateAppendix(this.dataInsert)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo phụ lục hợp đồng thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.loading = false
            this.reloadPage()
            this.resetDataContract()
        },

        async handleShowModalUI(action, id = null) {
            this.loading = true
            this.action = action
            if (action === 'create') {
                this.titleModal = 'Thêm mới'
                this.resetData()
            } else {
                this.titleModal = 'Cập nhật'
                this.id = id
                const response = await window.apiShowAppendix(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.dataInsert = this.formatDateAppendix(response.data.data)
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
            this.dataInsert = this.formatDateAppendix(response.data.data)
            $('#'+this.idModalInfo).modal('show');
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

        resetData() {
            this.dataInsert = {
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
            this.filters = {
                name_code: null,
                contract_ids: [],
                status: [],
                signing_date: null,
                from : null,
                limit: 10,
                page: 1
            }

            this.getList(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterContract') {
                    this.filters.contract_ids = value
                } else if (event.target.id === 'filterStatusContract') {
                    this.filters.status = value
                } else if (event.target.id === 'selectUserId') {
                    this.dataInsert.user_ids = value
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
                this.dataInsert.signing_date = storageFormat
            } else if(el.id === 'selectFrom') {
                this.dataInsert.from = storageFormat
            } else if(el.id === 'selectTo') {
                this.dataInsert.to = storageFormat
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

            this.dataInsert.files = this.dataInsert.files.concat(files)
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

        formatDateAppendix(appendix) {
            appendix.signing_date = appendix.signing_date !== null ? format(appendix.signing_date, 'dd/MM/yyyy') : null
            appendix.from = appendix.from !== null ? format(appendix.from, 'dd/MM/yyyy') : null
            appendix.to = appendix.to !== null ? format(appendix.to, 'dd/MM/yyyy') : null
            return appendix
        },

        initYearpicker() {
            document.querySelectorAll('.yearpicker').forEach(el => {
                new AirDatepicker(el, {
                    view: 'years', // Hiển thị danh sách năm khi mở
                    minView: 'years', // Giới hạn chỉ cho phép chọn năm
                    dateFormat: 'yyyy', // Định dạng chỉ hiển thị năm
                    autoClose: true, // Tự động đóng sau khi chọn năm
                    clearButton: true, // Nút xóa để bỏ chọn
                    onSelect({date}) {
                        console.log("Năm đã chọn:", date.getFullYear());
                    },
                });
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {

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
