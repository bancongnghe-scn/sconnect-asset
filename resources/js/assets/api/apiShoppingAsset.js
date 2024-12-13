window.apiSentInfoShoppingAsset = async function (shoppingPlanCompanyId, assets) {
    try {
        const response = await axios.post("/api/shopping-asset/sent-info",{
            shopping_plan_company_id: shoppingPlanCompanyId,
            assets: assets
        })

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

window.apiApprovalShoppingAsset = async function (ids, status) {
    try {
        const response = await axios.post("/api/shopping-asset/approval",{
            ids: ids,
            status: status
        })

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

