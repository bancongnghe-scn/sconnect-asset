document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_arise_company_update', (id) => ({
        init() {
            this.findShoppingArise()
            this.getListAssetType()
            this.getListJob()
            this.setConfigButtons()
        },

        list_asset_type: [],
        list_job: [],
        list_supplier: [],
        data: [],
        selectedRow: [],
        assetSynthetic: {
            new: [],
            rotation: []
        },

        async findShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiFindShoppingArise(id)
                if (response.success) {
                    this.data = response.data.data
                    if (![STATUS_SHOPPING_ARISE_PENDING_PROCESSING, STATUS_SHOPPING_ARISE_HR_PROCESSING].includes(this.data.status)) {
                        this.syntheticAssets()
                        this.getListSupplier()
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

        async updateShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiUpdateShoppingArise(this.data, id)
                if (response.success) {
                    toast.success('Cập nhật đề xuất mua sắm thành công !')
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

        async getListJob(){
            this.loading = true
            try {
                const response = await window.apiGetListJob({})
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

        async hrProcessingShoppingArise(){
            this.loading = true
            try {
                const response = await window.apiHrProcessingShoppingArise(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data.status = STATUS_SHOPPING_ARISE_HR_PROCESSING
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async syntheticShoppingArise(){
            this.loading = true
            try {
                const response = await window.apiSyntheticShoppingArise(id)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
                this.data.status = STATUS_SHOPPING_ARISE_HR_SYNTHETIC
                this.syntheticAssets()
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async getListSupplier() {
            this.loading = true
            try {
                const response = await window.apiGetSupplier({})
                if (!response.success) {
                    toast.success(response.message)
                    return
                }

                this.list_supplier = response.data.data.data
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        async sendApprovalShoppingArise() {
            this.loading = true
            try {
                const response = await window.apiSendApprovalShoppingArise(id)
                if (!response.success) {
                    toast.success(response.message)
                    return
                }

                this.findShoppingArise()
            } catch (e) {
                toast.error(e)
            } finally {
                this.loading = false
            }
        },

        syntheticAssets() {
             this.data.assets.forEach((item) => {
                 if (+item.action === SHOPPING_ASSET_ACTION_NEW) {
                     this.assetSynthetic.new.push(item)
                 } else {
                     this.assetSynthetic.rotation.push(item)
                 }
             })
        },

        setConfigButtons() {
            this.configButtons = [
                {
                    condition: () => [
                        STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                        STATUS_SHOPPING_ARISE_PENDING_MANAGER
                    ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Hoàn thành',
                            class: 'btn btn-sc',
                            action: () => 'this.completeShoppingArise()',
                            permission: 'shopping_arise.hr_processing'
                        },
                    ],
                },
                {
                    condition: () =>
                        [
                            STATUS_SHOPPING_ARISE_HR_PROCESSING,
                            STATUS_SHOPPING_ARISE_HR_SYNTHETIC,
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR,
                            STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                            STATUS_SHOPPING_ARISE_PENDING_MANAGER,
                        ].includes(+this.data.status),
                    buttons: [
                        {
                            text: 'Lưu',
                            class: 'btn btn-sc',
                            action: () => this.updateShoppingArise(),
                            permission: 'shopping_arise.hr_processing'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_ARISE_PENDING_PROCESSING,
                    buttons: [
                        {
                            text: 'Xử lý',
                            class: 'btn btn-sc',
                            action: () => this.hrProcessingShoppingArise(),
                            permission: 'shopping_arise.hr_processing'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_ARISE_HR_PROCESSING,
                    buttons: [
                        {
                            text: 'Tổng hợp',
                            class: 'btn btn-primary',
                            action: () => this.syntheticShoppingArise(),
                            permission: 'shopping_arise.hr_synthetic'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_ARISE_HR_SYNTHETIC,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalShoppingArise(),
                            permission: 'shopping_arise.hr_synthetic'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_ARISE_PENDING_MANAGER_HR,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalShoppingArise(),
                            permission: 'shopping_asset.hr_send_approval'
                        },
                    ],
                },
                {
                    condition: () => +this.data.status === STATUS_SHOPPING_ARISE_PENDING_ACCOUNTANT,
                    buttons: [
                        {
                            text: 'Gửi duyệt',
                            class: 'btn btn-primary',
                            action: () => this.sendApprovalShoppingArise(),
                            permission: 'shopping_asset.account_send_approval'
                        },
                    ],
                },
            ]
        }
    }))
})
