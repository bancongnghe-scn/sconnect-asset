window.apiGetListOrder = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        if (filtersFormat.created_at !== null) {
            filtersFormat.created_at = formatDate(filtersFormat.created_at)
        }
        if (filtersFormat.status !== null) {
            filtersFormat.status = [filtersFormat.status]
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

