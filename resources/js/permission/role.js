import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('role', () => ({
        init() {

        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên',
            description: 'Mô tả',
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
            name: null,
            limit: 10,
            page: 1
        },
        role: {
            name: null,
            description: null,
        },
        titleModal: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalUI: "idModalUI",

        //methods
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
            const response = await window.apiUpdateAppendix(this.appendix, this.id)
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
            const response = await window.apiCreateAppendix(this.appendix)
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
                this.appendix = this.formatDateAppendix(response.data.data)
            }

            $('#'+this.idModalUI).modal('show');
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
            this.role = {
                name: null,
                description: null
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
    }));
});
