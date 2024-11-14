import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('register_shopping_plan_organization_year', () => ({
        init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.getInfo()
            this.getListAssetType()
        },

        //data
        id: null,
        table_index: [],
        data: {
            name: null,
            organization_name: null,
            start_time : null,
            end_time : null,
            status: null,
            register_time: null
        },
        list_asset_type: [],

        //methods
        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data = response.data
                this.data.register_time = format(this.data.start_time, 'dd/MM/yyyy') + ' - ' + format(this.data.end_time, 'dd/MM/yyyy')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListAssetType() {
            this.loading = true
            const response = await window.apiGetAssetType({})
            if (response.success) {
                this.list_asset_type = response.data.data
            } else {
                toast.error('Lấy danh sách loại tài sản thất bại !')
            }
            this.loading = false
        },

        handleShowTable(index) {
            if (!this.table_index.includes(index)) {
                this.table_index.push(index)
            } else {
                this.table_index = this.table_index.filter(item => item !== index);
            }
        }
    }));
});
