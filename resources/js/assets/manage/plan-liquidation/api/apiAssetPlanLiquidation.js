window.apiGetPlanLiquidation = async function (filters) {
    try {

        const res = await axios.get("/api/manage-plan-liquidation/get", {
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

window.apiShowPlanLiquidation = async function (id) {
    try {
        const response = await axios.get("/api/manage-plan-liquidation/detail/"+id)

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

window.apiGetAssetLiquidationForModal = async function () {
    try {

        const res = await axios.get("/api/manage-asset-liquidation", {})

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

window.apiUpdateAssettoPlanLiquidation = async function (dataUpdate) {
    try {
        const response = await axios.post("/api/manage-plan-liquidation/update-asset",dataUpdate)

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

window.apiRemoveAssetFromPlanLiquidation = async function (plan_maintain_asset_id) {
    try {
        const response = await axios.delete("/api/manage-plan-liquidation/delete-asset/"+plan_maintain_asset_id)

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

window.apiRemoveMultiPlanLiquidation = async function (planIds) {
    
    try {
        const response = await axios.post("/api/manage-plan-liquidation/delete-multi",planIds)

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

window.apiCreatePlanLiquidation = async function (dataCreate) {
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

window.apiUpdatePlanLiquidation = async function (id, dataUpdate) {
    try {
        const response = await axios.post("/api/manage-plan-liquidation/update-plan/"+id,dataUpdate)

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

window.apiUpdatePlanLiquidationAsset = async function (dataUpdate) {
    try {
        const response = await axios.post("/api/manage-plan-liquidation/update-status-asset",dataUpdate)

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

window.apiUpdateMultiAssetOfPlan = async function (dataUpdate) {
    try {
        const response = await axios.post("/api/manage-plan-liquidation/update-status-multi-asset",dataUpdate)

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

window.checkDisableSelectRowOfModalShowPlan = function checkDisableSelectRowOfModalShowPlan() {
    const ids = Object.keys(this.selectedRowOfModalShowPlan).filter(key => this.selectedRowOfModalShowPlan[key] === true)
    return ids.length === 0
}