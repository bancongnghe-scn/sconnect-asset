document.addEventListener('alpine:init', () => {
    Alpine.data('register_shopping_plan_organization_week', () => ({
        init() {
            const split = window.location.href.split('/')
            this.id = split.pop();
            this.getInfo()
            this.getRegisterAsset()
            this.getListAssetType()
        },

        //data
        id: null,
        data: {
            name: null,
            organization_name: null,
            organization_id: null,
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
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.getJobs([this.data.organization_id])
            }
        },

        async getJobs(organization_id){
            this.loading = true
            try {
                const response = await window.apiGetListJob({org_id: organization_id})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.list_job = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(this.id)
                if (response.success) {
                    this.registers = response.data
                    this.registers = this.registers.map(register => ({
                        ...register,
                        receiving_time: register.receiving_time ? format(register.receiving_time, 'dd/MM/yyyy') : null
                    }))
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
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
                this.loading = false
            }
        },

        async sentRegister() {
            this.loading = true
            try {
                const response = await window.apiSentRegisterWeek(this.id, this.registers)
                if (response.success) {
                    toast.success('Đăng ký mua sắm thành công')
                    this.getRegisterAsset()
                    if (+this.data.status === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER) {
                        this.data.status = STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
                    }
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async saveReviewRegisterAsset() {
            this.loading = true
            try {
                const response = await window.apiSaveReviewRegisterAsset(this.id, this.registers)
                if (response.success) {
                    toast.success('Lưu thông tin phê duyệt thành công')
                    this.data.status = STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async accountApprovalShoppingPlanOrganization(id, type) {
            this.loading = true
            try {
                const response = await window.apiAccountApprovalShoppingPlanOrganization([id], type)
                if (response.success) {
                    this.data.status = type === ORGANIZATION_TYPE_APPROVAL
                        ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                    toast.success('Duyệt thành công !')
                    return
                }

                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        addRow() {
            this.registers.push({
                id_fake: Date.now() + Math.random(),
                asset_type_id: null,
                measure: null,
                quantity_registered: null,
                job_id: null,
                receiving_time: null,
                description: null
            })
        },

        deleteRow(index) {
            this.registers.splice(index,1)
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

        validateQuantityRegistered(value) {
            if (+value < 1) {
                toast.error('Số lượng đăng ký phải lớn hơn 0')
            }
        }
    }));
});