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
        let dataCreateFormat = JSON.parse(JSON.stringify(dataCreate))
        if (dataCreateFormat.delivery_date !== null) {
            dataCreateFormat.delivery_date = formatDate(dataCreateFormat.delivery_date)
        }
        if (dataCreateFormat.payment_time !== null) {
            dataCreateFormat.payment_time = formatDate(dataCreateFormat.payment_time)
        }
        const response = await axios.post("/api/order/create", dataCreateFormat)

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

