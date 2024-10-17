document.addEventListener('alpine:init', () => {
    Alpine.data('menu', () => ({
        init() {
            this.list({page: 1, limit: 10})
            this.getRole()
            this.getMenuParent()
            this.initSelect2Modal(this.idModalUI);
            this.onChangeSelect2()
        },

        //dataTable
        dataTable: [],
        columns: {
            name: 'Tên',
            roles: 'Vai trò',
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
        total: 0,
        from: 0,
        to: 0,
        limit: 10,
        showChecked: false,

        //data
        filters: {
            name: null,
            role_ids: [],
            limit: 10,
            page: 1
        },
        menu: {
            name: null,
            icon: null,
            url: null,
            order: null,
            parent_id: null,
            description: null,
            role_ids: [],
        },

        listRole: [],
        listMenuParent: [],
        titleModal: null,
        action: null,
        id: null,
        idModalConfirmDelete: "idModalConfirmDelete",
        idModalUI: "idModalUI",

        //methods
        async list(filters){
            this.loading = true
            const response = await window.apiGetMenu(filters)
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

        async edit() {
            this.loading = true
            const response = await window.apiUpdateMenu(this.menu, this.id)
            if (!response.success) {
                toast.error(response.message)
                this.loading = false
                return
            }
            toast.success('Cập nhập vai trò thành công !')
            $('#'+this.idModalUI).modal('hide');
            this.resetData()
            await this.list(this.filters)
            this.loading = false
        },

        async remove() {
            this.loading = true
            const response = await window.apiRemoveMenu(this.id)
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
            const response = await window.apiCreateMenu(this.menu)
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

        async getRole(){
            this.loading = true
            const response = await window.apiGetRole({})
            if (response.success) {
                this.listRole = response.data.data
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        async getMenuParent(){
            this.loading = true
            const response = await window.apiGetMenuParent()
            if (response.success) {
                this.listMenuParent = response.data.data
            } else {
                toast.error(response.message)
            }
            this.loading = false
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
                const response = await window.apiShowMenu(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.menu = response.data.data
            }

            $('#'+this.idModalUI).modal('show');
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
            this.menu = {
                name: null,
                icon: null,
                url: null,
                order: null,
                parent_id: null,
                description: null,
                role_ids: [],
            }
        },

        reloadPage() {
            this.filters = {
                name: null,
                role_ids: [],
                limit: 10,
                page: 1
            }

            this.list(this.filters)
        },

        confirmRemove(id) {
            $("#"+this.idModalConfirmDelete).modal('show');
            this.id = id
        },

        onChangeSelect2() {
            $('.select2').on('select2:select select2:unselect', (event) => {
                const value = $(event.target).val()
                if (event.target.id === 'filterRoles') {
                    this.filters.role_ids = value
                } else if (event.target.id === 'selectRoles') {
                    this.menu.role_ids = value
                } else if (event.target.id === 'selectIcon') {
                    this.menu.icon = value
                }
            });
        },
        initSelect2Modal(modalId) {
            $(`#${modalId}`).on('shown.bs.modal', function () {
                $('.select2').select2({
                    dropdownParent: $(`#${modalId}`),
                })

                $('.icon-select2').select2({
                    dropdownParent: $(`#${modalId}`),
                    templateResult: formatState,
                    templateSelection: formatState
                });

                function formatState (icon) {
                    if (!icon.id) {
                        return icon.text;
                    }
                    var $icon = $(
                        '<span class="d-flex gap-2 align-items-center"><i class="' + icon.id + '"></i>' + icon.text + '</span>'
                    );
                    return $icon;
                }
            })
        },
    }));
});
