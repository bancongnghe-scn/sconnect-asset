import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('register_shopping_plan_organization_year', () => ({
        init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.getInfo()
            this.getJobs()
            // this.getListAssetType()
        },

        //data
        id: null,
        search: null,
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
        list_job: [],
        registers : [],

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

        async getJobs(){
            this.loading = true
            try {
                const response = await window.apiGetAllJob()
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list_job = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                console.log('job')
                this.getListAssetType()
                this.loading = false
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                this.registers = [
                    {
                        assets: [
                            {id: 1, asset_type_id: 8, measure: 'Cái', job_id: 1, price: 1000, description: '111111', quantity_registered: 1, quantity_approved: 1},
                        ],
                        register: {price: 1000, total: 1},
                        approval: {price: 1000, total: 1},
                        month: 1
                    },
                    {
                        assets: [
                            {id: 1, asset_type_id: 9, measure: 'Cái', job_id: 1, price: 1000, description: null, quantity_registered: 1, quantity_approved: 1},
                        ],
                        register: {price: 1000, total: 1},
                        approval: {price: 1000, total: 1},
                        month: 2
                    },
                    {
                        assets: [],
                        register: {price: 0, total: 0},
                        approval: {price: 0, total: 0},
                        month: 3
                    },
                ]
            } catch (e) {
                toast.error(e)
            } finally {
                console.log('register')
                this.loading = false
            }
        },

        async getListAssetType() {
            this.loading = true
            try {
                const response = await window.apiGetAssetType({})
                if (response.success) {
                    this.list_asset_type = response.data.data
                    return
                }
                toast.error('Lấy danh sách loại tài sản thất bại !')
            } catch (e) {
                toast.error(e)
            } finally {
                console.log('asset_type')
                this.getRegisterAsset()
                this.loading = false
            }
        },

        async sentRegister() {
            this.loading = true
            try {
                const response = await window.apiSentRegisterYear(this.id, this.registers)
                if (!response.success) {
                    toast.error(response.message)
                }
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        handleShowTable(index) {
            if (!this.table_index.includes(index)) {
                this.table_index.push(index)
            } else {
                this.table_index = this.table_index.filter(item => item !== index);
            }
        },

        addRow(index) {
            this.registers[index].assets.push({
                id_fake: Date.now() + Math.random(),
                asset_type_id: null,
                measure: null,
                job_id: null,
                price: null,
                description: null,
                quantity_registered: null,
                quantity_approved: null
            })
        },

        deleteRow(index, key) {
            this.registers[index].assets.splice(key,1)
        },

        getPrice(asset_type_id, job_id) {
            if (!asset_type_id || !job_id) {
                return 0
            }
            return +(asset_type_id + job_id + 1000)
        },

        calculateRegister(index) {
            let total = 0
            let price = 0
            this.registers[index].assets.forEach((asset) => {
                total += +asset.quantity_registered
                price += (asset.quantity_registered * asset.price)
            })

            this.registers[index].register.total = total
            this.registers[index].register.price = price
        },

        calculateApproval(index) {
            let total = 0
            let price = 0
            this.registers[index].assets.forEach((asset) => {
                total += +asset.quantity_approved
                price += (asset.quantity_approved * asset.price)
            })

            this.registers[index].approval.total = total
            this.registers[index].approval.price = price
        },

        calculatePrice(index) {
            let price_register = 0
            let price_approval = 0
            this.registers[index].assets.forEach((asset) => {
                price_register += (asset.quantity_registered * asset.price)
                price_approval += (asset.quantity_approved * asset.price)
            })

            this.registers[index].approval.price = price_approval
            this.registers[index].register.price = price_register
        },
    }));
});
