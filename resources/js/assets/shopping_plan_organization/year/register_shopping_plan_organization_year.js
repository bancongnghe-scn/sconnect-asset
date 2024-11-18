import {format} from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.data('register_shopping_plan_organization_year', () => ({
        init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.getInfo()
            this.getListAssetType()
            this.getJobs()
            this.getRegisterAsset()

            // $('.select2').select2({
            //     language: {
            //         noResults: function() {
            //             return "Không tìm thấy kết quả";
            //         }
            //     }
            // })
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
                console.log('job')
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                this.registers = [
                    {
                        assets: [
                            {id: 1, asset_type_id: 8, measure: 1, job_id: 1, price: 1000, description: '111111', quantity_registered: 1, quantity_approved: 1},
                        ],
                        total_price: 1000,
                        total_asset: 1,
                        month: 1
                    },
                    {
                        assets: [
                            {id: 1, asset_type_id: 9, measure: 1, job_id: 1, price: 1000, description: null, quantity_registered: 1, quantity_approved: 1},
                        ],
                        total_price: 1000,
                        total_asset: 1,
                        month: 2
                    },
                    {
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 3
                    },
                    {
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 4
                    },
                    {
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 5
                    },
                    {
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 6
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 7
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 8
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 9
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 10
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 11
                    },{
                        assets: [],
                        total_price: 0,
                        total_asset: 0,
                        month: 12
                    },
                ]
                console.log('register')

                // this.registers.forEach(function (register, index) {
                //     register.assets.forEach(function (asset, key) {
                //         console.log('#select_asset_type_'+index+'_'+key)
                //         console.log(asset.asset_type_id)
                //          $('#select_asset_type_'+index+'_'+key).val(asset.asset_type_id).change()
                //     })
                // })
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
                console.log('asset_type')
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
        },

        addRow(index) {
            this.registers[index].assets.push({
                asset_type_id: 8,
                measure: null,
                job_id: null,
                price: null,
                description: null,
                quantity_registered: null,
                quantity_approved: null
            })
            const key =  this.registers[index].assets.length - 1
            setTimeout(function (){
                $('#select_asset_type_'+index+'_'+key).select2({
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                })

                $('#select_job_'+index+'_'+key).select2({
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                })
            }, 10)
        },

        deleteRow(index, key) {
            this.registers[index].assets.splice(key,1)
        }
    }));
});
