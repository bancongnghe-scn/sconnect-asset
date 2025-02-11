window.apiGetShoppingPlanCompanyQuarter = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-company/quarter/list", {
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

window.apiCreateShoppingPlanCompanyQuarter = async function (dataCreate) {
    try {
        const response = await axios.post("/api/shopping-plan-company/quarter/create",formatDateShoppingPlanCompanyQuarter(dataCreate))

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

window.apiUpdateShoppingPlanCompanyQuarter = async function (dataUpdate, id) {
    try {
        const response = await axios.put("/api/shopping-plan-company/quarter/update/"+id,formatDateShoppingPlanCompanyQuarter(dataUpdate))

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

window.getOrganizationRegisterQuarter = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-company/quarter/get-organization-register/"+id)

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

function formatDateShoppingPlanCompanyQuarter(data) {
    let dataFormat = data
    dataFormat.start_time = dataFormat.start_time ? window.formatDate(dataFormat.start_time) : null
    dataFormat.end_time = dataFormat.end_time ? window.formatDate(dataFormat.end_time) : null
    return dataFormat
}
