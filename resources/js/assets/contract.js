import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('contract', () => ({
        init() {
            this.list(this.filters)
            this.getListSupplier()
            this.watchFilters()
        },

        //dataTable
        dataTable: [],
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
            type: null,
            status: null,
            signing_date: {
                start: null,
                end: null,
            },
            from : {
                start: null,
                end: null,
            },
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
            contract_value: null,
            description: null,
            contract_link: null,
            user_ids: [],
            files: [],
            payments: [],
            appendixes: [],
        },
        listSupplier: [],
        title: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalConfirmDeleteMultiple: "idModalConfirmDeleteMultiple",
        idModalUI: "idModalUIContract",
        idModalInfo: "idModalInfoContract",

        //methods
        async list(filters) {
            this.loading = true
            try {
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
            } catch (e) {

            } finally {
                this.loading = false
            }
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

        async getListSupplier() {
            this.loading = true
            const response = await window.apiGetSupplier({})
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
            this.filters = {
                name_code: null,
                type: null,
                status: null,
                signing_date: {
                    start: null,
                    end: null,
                },
                from : {
                    start: null,
                    end: null,
                },
                limit: 10,
                page: 1
            }
            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        confirmRemoveMultiple() {
            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
            if (ids.length === 0) {
                toast.error('Vui lòng chọn ngành hàng cần xóa !')
                return
            }

            $("#"+this.idModalConfirmDeleteMultiple).modal('show');
            this.id = ids
        },

        handleFilesContract() {
            const files = Array.from(this.$refs.fileInputContract.files)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes

            for (let i = 0; i < files.length; i++) {
                if (files[i].size > maxSize) {
                    toast.error("File " + files[i].name + " vượt quá kích thước tối đa 5MB.")
                    return;
                }
            }

            this.data.files = this.data.files.concat(Array.from(this.$refs.fileInputContract.files))
        },

        addRowPayment() {
            this.data.payments.push({
                payment_date: null,
                money: null,
                description: null
            })
        },

        formatDataContract(contract) {
            contract.files = contract.files ?? []
            contract.appendixes = contract.appendixes ?? []
            contract.payments = contract.payments ?? []
            return contract
        },

        watchFilters() {
            this.$watch('filters.signing_date', (value) => {
                if (value.start !== null && value.end !== null) {
                    this.list(this.filters);
                }
            })

            this.$watch('filters.from', (value) => {
                if (value.start !== null && value.end !== null) {
                    this.list(this.filters);
                }
            })

            this.$watch('filters', (value) => {
                const watchedKeys = ['type', 'status'];
                const shouldCallList = watchedKeys.some((key) => value[key] !== null);
                if (shouldCallList) {
                    this.list(this.filters);
                }
            }, { deep: true });
        }
    }));
});
