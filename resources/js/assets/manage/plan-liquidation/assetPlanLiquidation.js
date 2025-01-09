import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en";
import { format, parse, isValid } from "date-fns";

document.addEventListener('alpine:init', () => {
    Alpine.store('assetPlanLiquidation', {
        instance: null,
        dataAssetDraftForCreatePlanLiquidation: [],
    });
    Alpine.data('tableAssetPlanLiquidation', () => ({
        init() {
            this.list({ page: 1, limit: 10 })
            Alpine.store('assetPlanLiquidation').instance = this
            this.initDatePicker()
            this.filterPlanLiquidation()
        },

        dataTable: [],
        columns: {
            code: 'Mã kế hoạch',
            name: 'Tên kế hoạch',
            asset_quantity: 'Số lượng tài sản',
            created_at: 'Ngày tạo',
            total_price_liquidation: 'Tổng giá trị tài sản thanh lý',
            status: 'Trạng thái',
        },
        showAction: {
            create: true,
            edit: true,
            get: true,
            approve: true,
            cancel: true
        },

        //pagination
        totalPages: null,
        currentPage: 1,
        total: 0,
        from: 0,
        to: 0,
        limit: 25,
        selectedRow: [],

        //data
        filters: {
            name_code: null,
            status: null,
            date: null,
            limit: 25,
            page: 1
        },
        data: {
            code: null,
            name: null,
            status: null,
            created_at: null,
            asset_quantity: null,
            total_price_liquidation: null,
        },
        listStatusPlanLiquidation: {
            0: 'Chọn trạng thái',
            1: 'Mới tạo',
            2: 'Chờ duyệt',
            3: 'Đã duyệt',
            4: 'Từ chối',
        },
        listStatusAssetOfPlan: {
            1: 'Chưa duyệt',
            2: 'Đã duyệt',
            3: 'Từ chối'
        },
        id: null,
        selectedValue: null,
        idModalEditPlanLiquidation: "idModalEditPlanLiquidation",
        idModalSelectAsset: "idModalSelectAsset",

        idModalShowPlanLiquidation: "idModalShowPlanLiquidation",
        statusPlanLiquidation: "statusPlanLiquidation",
        filterSigningDate: "filterSigningDate",

        // Modal plan
        dataTbodyListAssetLiqui: [],
        dataTheadListAssetLiqui: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            reason: 'Lý do thanh lý',
            price: 'Giá đề xuất thanh lý',
            status: 'Trạng thái'
        },
        selectedRowOfModalShowPlan: [],

        // Modal select asset
        dataTbodySelectAsset: [],
        dataTheadSelectAsset: {
            code: 'Mã tài sản',
            name: 'Tên tài sản',
            reason: 'Lý do thanh lý',
            price_liquidation: 'Giá đề xuất thanh lý',
        },
        selectedRowAssetToPlan: [],
        reasonCancel: "",
        idCancel: "",
        multiCancel: false,
        checkCreate: false,

        assetsLiquidationCount: "assetsLiquidationCount",

        //methods
        async list(filters) {
            this.loading = true

            const response = await window.apiGetPlanLiquidation(filters)
            if (response.success) {
                const data = response.data
                this.dataTable = data.data.data
                this.totalPages = data.data.last_page
                this.currentPage = data.data.current_page
                this.total = data.data.total ?? 0
                this.from = data.data.from ?? 0
                this.to = data.data.to ?? 0

                $('#' + this.assetsLiquidationCount).text(`(${data.data.total ?? 0})`)
            } else {
                toast.error(response.message)
            }
            this.loading = false
        },

        formatDate(date) {
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },

        // Edit Plan liquidation
        async handleEditModalUI(id) {
            this.loading = true
            this.id = id

            const response = await window.apiShowPlanLiquidation(id)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.data = response.data.data
            this.dataTbodyListAssetLiqui = this.data?.plan_maintain_asset

            $('#' + this.idModalEditPlanLiquidation).modal('show');
            this.loading = false
        },

        // Open modal select asset to plan liquidation
        async modalSelectAsset() {
            const response = await window.apiGetAssetLiquidationForModal()

            this.dataTbodySelectAsset = response.data.data

            if (!this.id && Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation) {
                const ids_selected_pre = Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation.map(item => item.id)
                this.dataTbodySelectAsset = this.dataTbodySelectAsset.filter(item => !ids_selected_pre.map(Number).includes(item.id))
            }

            $('#' + this.idModalSelectAsset).modal('show');
        },

        // Update asset to plan liquidation
        async addAssetToPlanLiquidation() {
            const asset_ids = Object.keys(this.selectedRowAssetToPlan).filter(key => this.selectedRowAssetToPlan[key] === true)
            const dataUpdate = {
                'plan_id': this.id,
                'asset_ids': asset_ids
            }

            if (this.id) {
                // Update
                const response = await window.apiUpdateAssettoPlanLiquidation(dataUpdate)
                if (!response.success) {
                    toast.error(response.message)
                    return
                }

                // Update modal select asset
                const res = await window.apiShowPlanLiquidation(this.id)
                if (!res.success) {
                    toast.error(res.message)
                    return
                }
                this.data = res.data.data
                this.dataTbodyListAssetLiqui = this.data?.plan_maintain_asset
            } else {
                // Create
                const data_asset_liquidation = this.dataTbodySelectAsset;
                const data_asset_liquidation_prev = Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation

                if (data_asset_liquidation_prev && data_asset_liquidation_prev.length > 0) {
                    Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation = [
                        ...data_asset_liquidation_prev,
                        ...data_asset_liquidation.filter(item => asset_ids.includes(String(item.id)))
                    ]
                } else {
                    Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation = data_asset_liquidation.filter(item => asset_ids.includes(String(item.id)))
                }
            }

            this.selectedRowAssetToPlan = []

            $('#' + this.idModalSelectAsset).modal('hide');
        },

        async modalRemoveSelectAsset(id) {
            this.loading = true

            const plan_maintain_asset_id = id
            if (this.id) {
                this.dataTbodyListAssetLiqui = this.dataTbodyListAssetLiqui.filter(item => item.id !== plan_maintain_asset_id);
                const response = await window.apiRemoveAssetFromPlanLiquidation(plan_maintain_asset_id);
                if (!response.success) {
                    toast.error(response.message)
                    return
                }
            } else {
                Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation =
                    Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation
                        .filter(item => item.id !== plan_maintain_asset_id)
            }

            this.loading = false
        },

        async handleShowModalCreatePlan() {
            this.loading = true

            this.id = null
            this.checkCreate = true
            $('#' + this.idModalEditPlanLiquidation).modal('show')
            this.dataTbodyListAssetLiqui = []

            this.loading = false
        },

        async createPlan() {
            this.loading = true

            const data_asset_liquidation_prev = Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation
            let response

            if (data_asset_liquidation_prev && data_asset_liquidation_prev.length > 0) {
                const assets_id = {
                    assets_id: data_asset_liquidation_prev.map(item => ({
                        id: item.id,
                        price_liquidation: item.price_liquidation
                    }))
                };

                response = await window.apiCreatePlanLiquidation({
                    ...assets_id,
                    ...this.data
                })
            } else {
                if (this.data.code && this.data.name) {
                    response = await window.apiCreatePlanLiquidation(this.data)
                } else {
                    if (!this.data.code && !this.data.name) {
                        toast.error('Vui lòng điền mã và tên kế hoạch thanh lý!')
                    } else {
                        if (!this.data.code) {
                            toast.error('Vui lòng điền mã kế hoạch thanh lý!')
                        }
                        if (!this.data.name) {
                            toast.error('Vui lòng điền tên kế hoạch thanh lý!')
                        }
                    }
                    return
                }
            }

            if (!response.success) {
                toast.error(response.message)
                this.loading = false
                return
            }

            this.dataTbodyListAssetLiqui = []
            Alpine.store('globalData').dataAssetDraftForCreatePlanLiquidation = []
            this.list(this.filters)
            $('#' + this.idModalEditPlanLiquidation).modal('hide');
            this.checkCreate = false
            this.loading = false
        },

        async updatePlan(id) {
            this.loading = true
            const _data = {
                name: this.data['name'] ?? '',
                code: this.data['code'] ?? '',
                note: this.data['note'] ?? '',
            }
            const response = await window.apiUpdatePlanLiquidation(id, _data)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.list(this.filters)
            this.id = null
            $('#' + this.idModalEditPlanLiquidation).modal('hide');
            this.loading = false
        },

        async confirmRemoveMultiplePlan() {
            this.loading = true

            const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)

            const dataRemove = {
                plan_ids: ids
            }

            const response = await window.apiRemoveMultiPlanLiquidation(dataRemove)
            if (!response.success) {
                toast.error(response.message)
                return
            }

            this.dataTable = this.dataTable.filter(item => !ids.map(Number).includes(item.id));

            this.loading = false
        },

        async removeOnePlan(id) {
            const dataRemove = {
                plan_ids: [id]
            }

            const response = await window.apiRemoveMultiPlanLiquidation(dataRemove)
            if (!response.success) {
                toast.error(response.message)
                return
            }

            this.dataTable = this.dataTable.filter(item => item.id !== id);

            this.loading = false

        },

        async sendForApproval(planId) {
            this.loading = true
            const _data = {
                name: this.data['name'] ?? '',
                code: this.data['code'] ?? '',
                note: this.data['note'] ?? '',
                status: +Object.entries(this.listStatusPlanLiquidation).find(([key, value]) => value === 'Chờ duyệt')?.[0]
            }

            const response = await window.apiUpdatePlanLiquidation(planId, _data)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.list(this.filters)

            this.loading = false
        },

        async showPlanLiquidation(planId) {
            this.loading = true

            this.id = planId
            const response = await window.apiShowPlanLiquidation(planId)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.data = response.data.data
            this.dataTbodyListAssetLiqui = this.data?.plan_maintain_asset

            $('#' + this.idModalShowPlanLiquidation).modal('show');

            this.loading = false
        },

        async showCancel(id, multi = false) {
            this.idCancel = id
            if (multi) {
                this.multiCancel = true
            }

            $('#idshowCancel').modal('show');
        },

        async handleUpdateAssetOfPlan(id, status_name) {
            this.loading = true

            const status_asset = Number(Object.keys(this.listStatusAssetOfPlan).find(
                k => this.listStatusAssetOfPlan[k] === status_name
            ))

            const approve = {
                id: id,
                status: status_asset,
                reason_cancel: this.reasonCancel
            }

            const response = await window.apiUpdatePlanLiquidationAsset(approve)
            if (!response.success) {
                toast.error(response.message)
                return
            }

            this.dataTbodyListAssetLiqui.forEach(item => {
                if (item.id === id) {
                    item.status = status_asset;
                }
            });
            this.reasonCancel = ""
            this.idCancel = ""
            this.loading = false
        },

        async handleUpdateAssetOfPlanMulti(action) {
            this.loading = true

            const status_asset = Number(Object.keys(this.listStatusAssetOfPlan).find(
                k => this.listStatusAssetOfPlan[k] === (action == 'approve' ? 'Đã duyệt' : 'Từ chối')
            ))
            const ids = Object.keys(this.selectedRowOfModalShowPlan).filter(key => this.selectedRowOfModalShowPlan[key] === true)
            const dataUpdate = {
                ids: ids,
                status: status_asset,
                reason_cancel: this.reasonCancel
            }

            const response = await window.apiUpdateMultiAssetOfPlan(dataUpdate)
            if (!response.success) {
                toast.error(response.message)
                return
            }

            this.dataTbodyListAssetLiqui.forEach(item => {
                if (ids.map(Number).includes(item.id)) {
                    item.status = status_asset
                    item.note   = this.reasonCancel
                }
            });
            this.selectedRowOfModalShowPlan = []
            if ($('.manage_assets #selectedAllAssetOfPlanLiqui').is(':checked')) {
                $('.manage_assets #selectedAllAssetOfPlanLiqui').click();
            }

            this.reasonCancel = ""
            this.multiCancel = false
            this.loading = false
        },

        async confirmPlan($action) {
            this.loading = true
            const _data = {
                status: Number(Object.keys(this.listStatusPlanLiquidation).find(
                    k => this.listStatusPlanLiquidation[k] === $action
                ))
            }

            const response = await window.apiUpdatePlanLiquidation(this.id, _data)
            if (!response.success) {
                toast.error(response.message)
                return
            }
            this.list(this.filters);
            $('#' + this.idModalShowPlanLiquidation).modal('hide');

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

        initDatePicker() {
            document.querySelectorAll('.datepicker').forEach(el => {
                new AirDatepicker(el, {
                    autoClose: true,
                    clearButton: true,
                    locale: localeEn,
                    view: 'months',
                    minView: 'months',
                    dateFormat: 'MMMM yyyy',
                    onSelect: ({ date }) => {
                        const created_at = $('#tableAssetPlanLiquidation #filterSigningDate').val();
                        const created_at_format = created_at
                            ? format(parse(created_at, 'MMMM yyyy', new Date()), 'yyyy-MM')
                            : null;
                        this.list({
                            page: 1,
                            limit: 25,
                            name_code: $('#tableAssetPlanLiquidation #namecodePlanLiquidation').val(),
                            status: $('#tableAssetPlanLiquidation #statusPlanLiquidation').val(),
                            created_at: created_at_format,
                        })

                        this.onChangeDatePicker(el, date)
                    }
                });

                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        setTimeout(() => {
                            if (!el.value) {
                                this.onChangeDatePicker(el, null);
                            }
                        }, 0);
                    }
                });
            });
        },

        onChangeDatePicker(el, date) {
            const storageFormat = date != null ? format(date, 'yyyy-MM-dd') : null
            if (el.id === 'filterSigningDate') {
                this.filters.signing_date = date != null ? format(date, 'yyyy-MM') : null;
            } else if (el.id === 'filterFrom') {
                this.filters.from = storageFormat
            } else if (el.id === 'selectSigningDate') {
                this.data.signing_date = storageFormat
            } else if (el.id === 'selectFrom') {
                this.data.from = storageFormat
            } else if (el.id === 'selectTo') {
                this.data.to = storageFormat
            }
        },

        formatDate(date) {
            if (!date) {
                date = new Date();
            }

            // Tạo đối tượng Date và định dạng theo d/m/Y ngắn gọn
            const parsedDate = new Date(date);
            return isNaN(parsedDate) ? '' : parsedDate.toLocaleDateString('en-GB');
        },

        filterPlanLiquidation() {
            $('#tableAssetPlanLiquidation #' + this.statusPlanLiquidation + ', #tableAssetPlanLiquidation #' + this.filterSigningDate).on('change', function () {
                let name_code = $('#tableAssetPlanLiquidation #namecodePlanLiquidation').val();
                let status = $('#tableAssetPlanLiquidation #statusPlanLiquidation').val();
                let created_at = $('#tableAssetPlanLiquidation #filterSigningDate').val();
                const created_at_format = created_at
                    ? format(parse(created_at, 'dd/MM/yyyy', new Date()), 'yyyy-MM-dd')
                    : null;
                Alpine.store('assetPlanLiquidation').instance.list({
                    page: 1,
                    limit: 25,
                    name_code: name_code,
                    status: status,
                    created_at: created_at_format
                });
            })
        }
    }))
})