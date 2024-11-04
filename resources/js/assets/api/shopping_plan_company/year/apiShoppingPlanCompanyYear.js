window.apiGetShoppingPlanCompanyYear = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-company/year/list", {
            params: filters
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

window.apiCreateShoppingPlanCompanyYear = async function (dataCreate) {
    try {
        const response = await axios.post("/api/shopping-plan-company/year/create",formatDateShoppingPlanCompanyYear(dataCreate))

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

window.apiUpdateShoppingPlanCompanyYear = async function (dataUpdate, id) {
    try {
        const response = await axios.put("/api/shopping-plan-company/year/update/"+id,formatDateShoppingPlanCompanyYear(dataUpdate))

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

function formatDateShoppingPlanCompanyYear(data) {
    let dataFormat = data
    dataFormat.start_time = dataFormat.start_time ? window.formatDate(dataFormat.start_time) : null
    dataFormat.end_time = dataFormat.end_time ? window.formatDate(dataFormat.end_time) : null
    console.log(data)
    return dataFormat
}
