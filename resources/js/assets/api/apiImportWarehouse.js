window.apiGetAssetForImportWarehouse = async function (orderIds) {
    try {
        const response = await axios.get("/api/import-warehouse/asset", {params: {'order_ids': orderIds}})

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
