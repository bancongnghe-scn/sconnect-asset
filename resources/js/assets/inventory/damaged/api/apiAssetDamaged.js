window.apiGetAssetDamaged = async function (filters) {
    try {
        const res = await axios.get("/api/asset-damaged/list", {
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

window.apiCreateAssetRepair = async function (dataCreate) {
    try {

        const res = await axios.post("/api/asset-repair/create", dataCreate)

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

window.apiUpdateAssetLiquidation = async function (dataUpdate) {
    try {

        const res = await axios.post("/api/asset-damaged/update-asset-liquidation", dataUpdate)

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

window.apiUpdateAssetCancel = async function (dataUpdate) {
    try {

        const res = await axios.post("/api/asset-damaged/update-asset-cancel", dataUpdate)

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

window.checkDisableSelectRowOfModalShowPlan = function checkDisableSelectRowOfModalShowPlan() {
    const ids = Object.keys(this.selectedRowOfModalShowPlan).filter(key => this.selectedRowOfModalShowPlan[key] === true)
    return ids.length === 0
}