window.apiGetAssetForImportWarehouse = async function (orderId) {
    try {
        const response = await axios.get("/api/import-warehouse/asset/"+orderId)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiGetImportWarehouse = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        filtersFormat.created_at = formatDate(filtersFormat.created_at)
        const response = await axios.get("/api/import-warehouse/list", {params: filtersFormat})

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiCreateImportWarehouse = async function (dataCreate) {
    try {
        const response = await axios.post("/api/import-warehouse/create", dataCreate)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiGetInfoImportWarehouse = async function (id) {
    try {
        const response = await axios.get("/api/import-warehouse/info/"+id)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}


