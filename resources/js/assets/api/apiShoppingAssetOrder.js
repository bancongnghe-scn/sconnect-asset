window.apiGetShoppingAssetOrder = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-asset-order/list", {params: filters})

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
