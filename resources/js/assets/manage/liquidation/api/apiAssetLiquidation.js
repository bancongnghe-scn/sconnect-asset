window.apiGetAssetLiquidation = async function (filters) {
    try {

        const res = await axios.get("/api/manage-asset-liquidation", {
            params: filters
        })

        const data = res.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiCreatePlanLiquidationFromSelectAsset = async function (dataCreate) {
    try {
        const response = await axios.post("/api/manage-plan-liquidation/create", dataCreate)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}
