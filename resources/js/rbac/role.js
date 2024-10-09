document.addEventListener('alpine:init', () => {
    Alpine.data('role', () => ({
        init() {
            window.initSelect2Modal(this.idModalUI);
            this.list({
                page: 1,
                limit: 10
            })
            this.getListPermission({})
            this.onChangeSelect2()
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
            user_ids: [],
            permission_ids: []
        },
        titleModal: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalUI: "idModalUI",
        listUser: [
            {id:1, name: 'User1'},
            {id:2, name: 'User2'},
        ],
        listPermission: [],

        //methods
        async list(filters){
            this.loading = true
            const response = await window.apiGetRole(filters)
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
            const response = await window.apiUpdateRole(this.role, this.id)
            if (!response.success) {
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
            const response = await window.apiRemoveRole(this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false

                return;
            }
            $("#"+this.idModalConfirmDelete).modal('hide')
            toast.success('Xóa vai trò thành công !')
            await this.list(this.filters)

            this.loading = false
        },

        async create() {
            this.loading = true
            const response = await window.apiCreateRole(this.role)
            if (!response.success) {
                this.loading = false
                toast.error(response.message)
                return
            }
            toast.success('Tạo vai trò thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.loading = false
            this.reloadPage()
            this.resetData()
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
                const response = await window.apiShowRole(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.role = response.data.data
            }

            $('#'+this.idModalUI).modal('show');
            this.loading = false
        },

        async getListPermission(filters) {
            this.loading = true
            const response = await window.apiGetPermission(filters)
            if (response.success) {
                this.listPermission = response.data.data
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'selectUsers') {
                    this.role.user_ids = value
                } else if (event.target.id === 'selectPermissions') {
                    this.role.permission_ids = value
                }
            });
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
            this.role = {
                name: null,
                description: null
            }
        },

        reloadPage() {
            this.filters = {
                name: null,
                limit: 10,
                page: 1
            }

            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },
    }));
});
