document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_organization_year_detail', (id) => ({
        async init() {
            this.getListAssetType()
            await this.getInfo()
            this.getRegisterAsset()
            this.setConfigButtons()
        },

        list_job: [],
        table_index: [],
        dataOrganization: [],
        registersOrganization: [],
        configButtonsOrganization: [],

        async getInfo(){
            this.loading = true
            try {
                const response = await window.apiGetInfoShoppingPlanOrganization(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.dataOrganization = response.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
                this.getJobs([this.dataOrganization.organization_id])
            }
        },

        async getRegisterAsset(){
            try {
                const response = await window.apiGetRegisterShoppingPlanOrganization(id)
                if (response.success) {
                    this.registersOrganization = response.data
                    return
                }
                toast.error(response.message)
            } catch (e) {
                toast.error(e)
            } finally {
            }
        },

        async getListAssetType() {
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
            }
        },

        async getJobs(organization_id){
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
            }
        },

        async saveReviewRegisterAsset() {
            this.loading = true
            try {
                const response = await window.apiSaveReviewRegisterAsset(id, this.registersOrganization)
                if (response.success) {
                    toast.success('Lưu thông tin phê duyệt thành công')
                    this.dataOrganization.status = STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
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
                    this.dataOrganization.status = type === ORGANIZATION_TYPE_APPROVAL
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

        handleShowTable(index) {
            if (index === 'expand') {
                this.table_index = [0,1,2,3,4,5,6,7,8,9,10,11]
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

        setConfigButtons() {
            this.configButtonsOrganization = [
                {
                    condition: () => [
                        STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
                        STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
                    ].includes(+this.dataOrganization.status),
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
                        STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
                    ].includes(+this.dataOrganization.status),
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
                    ].includes(+this.dataOrganization.status),
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
        }
    }))
})
