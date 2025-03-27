document.addEventListener('alpine:init', () => {
    Alpine.data('shopping_arise_list', () => ({
        init() {

        },

        //pagination
        dataTable: [],
        totalPages: null,
        currentPage: 1,
        from: 0,
        to: 0,
        total: 0,
        limit: 10,

        filters: {
            name: null,
            start_time: null,
            end_time: null,
            status: null,
        },
        selectedRow: [],
        list_asset_type: [],
        list_job: [],
        data: {
            name: null,
            assets: []
        },

        handleShowModalCreate() {
            this.data = {
                name: null,
                assets: []
            }
            $('#modalCreateShoppingArise').modal('show')
        },

        addRowAsset() {
            this.data.assets.push({
                asset_type_id: null,
                quantity_registered: null,
                job_id: null,
                receiving_time: null,
                description: null
            })
        }
    }))
})
