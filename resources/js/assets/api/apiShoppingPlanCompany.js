import {format} from "date-fns";

window.apiGetShoppingPlanCompany = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-company/list", {
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


window.apiRemoveShoppingPlanCompany = async function (id) {
    try {
        const response = await axios.delete("/api/shopping-plan-company/"+id)

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

window.apiRemoveShoppingPlanCompanyMultiple = async function (ids) {
    try {
        const response = await axios.post("/api/delete-multiple/shopping-plan-company",{ids: ids})

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

window.apiShowShoppingPlanCompany = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-company/show/"+id)

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

window.apiCreateShoppingPlanCompany = async function (dataCreate) {
    try {
        dataCreate.start_time = dataCreate.start_time ? format(dataCreate.start_time, 'yyyy-MM-dd') : null
        dataCreate.end_time = dataCreate.end_time ? format(dataCreate.end_time, 'yyyy-MM-dd') : null
        const response = await axios.post("/api/shopping-plan-company/year/create",dataCreate)

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

window.apiUpdateShoppingPlanCompany = async function (dataUpdate, id) {
    try {
        const response = await axios.put("/api/shopping-plan-company/"+id,dataUpdate)

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
