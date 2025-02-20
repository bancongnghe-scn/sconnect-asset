window.apiGetPlanMaintainLogById = async function (id) {
    try {
        const response = await axios.get("/api/plan-maintain-log/getPlanMaintainLogById/"+id)

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

window.getShoppingPlanLogByRecordId = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-log/get-by-id/"+id)

        return {
            success: true,
            data: response.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

