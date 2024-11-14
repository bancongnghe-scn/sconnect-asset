import {format} from "date-fns";

window.apiCreateShoppingPlanCompanyYear = async function (dataCreate) {
    try {
        const response = await axios.post("/api/shopping-plan-company/year/create",formatDate(dataCreate))

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
        const response = await axios.put("/api/shopping-plan-company/year/update/"+id,formatDate(dataUpdate))

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

function formatDate(data) {
    data.start_time = data.start_time ? format(data.start_time, 'yyyy-MM-dd') : null
    data.end_time = data.end_time ? format(data.end_time, 'yyyy-MM-dd') : null

    return data
}
