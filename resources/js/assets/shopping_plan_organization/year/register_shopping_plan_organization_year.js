import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('register_shopping_plan_organization_year', () => ({
        init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.getInfo()
        },

        //data
        id: null,
        data: {
            name: null,
            organization_name: null,
            time_register: null,
            status: null
        },

        //methods
        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(this.id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data.name = response.data.name
                this.data.organization_name = response.data.organization_name
                this.data.status = response.data.status
                this.data.time_register = format(response.data.start_time, 'dd/MM/yyyy') + ' - ' + format(response.data.end_time, 'dd/MM/yyyy')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },
    }));
});
