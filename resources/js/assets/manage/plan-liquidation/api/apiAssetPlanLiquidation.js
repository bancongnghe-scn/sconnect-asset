window.apiGetPlanLiquidation = async function (filters) {
    try {
        filters.name_code = filters.name_code ? filters.name_code : null;
        filters.status = filters.status ? filters.status : null;
        filters.created_at = filters.created_at ? filters.created_at : null;

        const res = await axios.get("/api/asset/manage/asset-plan-liquidation", {
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
        const response = await axios.get("/api/asset/manage/asset-plan-liquidation/"+id)

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

        const res = await axios.get("/api/asset/manage/asset-liquidation", {})

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
        const response = await axios.post("/api/asset/manage/asset-plan-liquidation/updateAssetToPlan",dataUpdate)

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
        const response = await axios.delete("/api/asset/manage/asset-plan-liquidation/"+plan_maintain_asset_id)

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
        const response = await axios.post("/api/asset/manage/asset-plan-liquidation/delete-multi",planIds)

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
        
        const _data = {
            name: dataCreate['name'],
            code: dataCreate['code'],
            note: dataCreate['note'],
        }

        const response = await axios.post("/api/asset/manage/asset-plan-liquidation",_data)

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
        const response = await axios.post("/api/asset/manage/asset-plan-liquidation/"+id,dataUpdate)

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
        const response = await axios.post("/api/asset/manage/asset-plan-liquidation/changeStatusAssetOfPlan",dataUpdate)

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
        const response = await axios.post("/api/asset/manage/asset-plan-liquidation/changeStatusMultiAssetOfPlan",dataUpdate)

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