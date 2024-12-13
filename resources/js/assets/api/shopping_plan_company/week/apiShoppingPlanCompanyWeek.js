window.apiGetShoppingPlanCompanyWeek = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-company/week/list", {
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

window.apiCreateShoppingPlanCompanyWeek = async function (dataCreate) {
    try {
        const response = await axios.post("/api/shopping-plan-company/week/create",formatDateShoppingPlanCompanyWeek(dataCreate))

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

window.apiUpdateShoppingPlanCompanyWeek = async function (dataUpdate, id) {
    try {
        const response = await axios.put("/api/shopping-plan-company/week/update/"+id,formatDateShoppingPlanCompanyWeek(dataUpdate))

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

window.getOrganizationRegisterWeek = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-company/week/get-organization-register/"+id)

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


window.apiHandleShoppingPlanWeek = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-company/week/handle-shopping/"+id)

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

window.apiSyntheticShoppingPlanWeek = async function (id, shoppingAssets) {
    try {
        const response = await axios.post("/api/shopping-plan-company/week/synthetic-shopping", {
            'shopping_plan_company_id': id,
            'shopping_assets': shoppingAssets
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

window.apiSendApprovalWeek = async function (nextStatus, id) {
    try {
        const response = await axios.post("/api/shopping-plan-company/week/send-approval", {
            shopping_plan_company_id: id,
            status: nextStatus
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


function formatDateShoppingPlanCompanyWeek(data) {
    let dataFormat = data
    dataFormat.start_time = dataFormat.start_time ? window.formatDate(dataFormat.start_time) : null
    dataFormat.end_time = dataFormat.end_time ? window.formatDate(dataFormat.end_time) : null
    return dataFormat
}
