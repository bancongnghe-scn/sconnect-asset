document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        init() {
            this.getMenus()
        },

        //data
        menus: [],

        //methods
        async getMenus(filters) {
            this.loading = true
            const response = await window.apiGetMenuUser()
            if (response.success) {
                this.menus = response.data.data
            } else {
                toast.error('Lấy menu thất bại !')
            }
            this.loading = false
        },
    }));
});
