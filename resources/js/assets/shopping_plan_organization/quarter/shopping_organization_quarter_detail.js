document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_organization_quarter_detail', (id) => ({
        async init() {
            await this.getInfo()
            this.getRegisterAsset()
            this.getListAssetType()
            this.setConfigButtons()
        },

        data: [],
        registers: [],
        list_job: [],
        table_index: [],
        list_asset_type: [],
        configButtonsDetail: [],

        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(id)
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
                const response = await window.apiGetListJob({'org_id': organization_id})
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                let data = response.data
                data.unshift(POSITION_ORGANIZATION)
                this.list_job = data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getRegisterAsset(){
            this.loading = true
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registers = response.data
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
                const response = await window.apiSaveReviewRegisterAsset(id, this.registers)
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

        async accountApprovalShoppingPlanOrganization(type) {
            this.loading = true
            try {
                const response = await window.apiAccountApprovalShoppingPlanOrganization([id], type)
                if (response.success) {
                    this.data.status = type === ORGANIZATION_TYPE_APPROVAL
                        ? STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL : STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
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

        setConfigButtons() {
            this.configButtonsDetail = [
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-primary',
                            action: () => this.saveReviewRegisterAsset(),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Duyệt',
                            class: 'btn bg-sc text-white',
                            action: () => this.accountApprovalShoppingPlanOrganization(ORGANIZATION_TYPE_APPROVAL),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Từ chối',
                            class: 'btn bg-red',
                            action: () => this.accountApprovalShoppingPlanOrganization(ORGANIZATION_TYPE_DISAPPROVAL),
                            permission: 'shopping_plan_company.accounting_approval'
                        },
                    ],
                },
            ]
        },

        handleShowTable(index) {
            if (index === 'expand') {
                this.table_index = [0,1,2]
                return;
            }

            if (index === 'decrease') {
                this.table_index = []
                return;
            }

            if (!this.table_index.includes(index)) {
                this.table_index.push(index)
            } else {
                this.table_index = this.table_index.filter(item => item !== index);
            }
        },
    }))
})
