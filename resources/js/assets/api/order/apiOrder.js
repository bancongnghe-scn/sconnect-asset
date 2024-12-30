window.apiGetListOrder = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        if (filtersFormat.created_at !== null) {
            filtersFormat.created_at = formatDate(filtersFormat.created_at)
        }

        const response = await axios.get("/api/order/list", {
            params: filtersFormat
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
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiCreateOrder = async function (dataCreate) {
    try {
        const response = await axios.post("/api/order/create", formatData(dataCreate))

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}


window.apiUpdateOrder = async function (dataUpdate) {
    try {
        const response = await axios.post("/api/order/update", formatData(dataUpdate))

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiFindOrder = async function (id) {
    try {
        const response = await axios.get("/api/order/find/"+id)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

window.apiRemoveOrder = async function (ids, reason) {
    try {
        const response = await axios.post("/api/order/delete", {
            ids: ids,
            reason: reason
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
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}

function formatData(data) {
    let dataFormat = JSON.parse(JSON.stringify(data))
    if (dataFormat.delivery_date !== null) {
        dataFormat.delivery_date = formatDate(dataFormat.delivery_date)
    }
    if (dataFormat.payment_time !== null) {
        dataFormat.payment_time = formatDate(dataFormat.payment_time)
    }
    return dataFormat
}

