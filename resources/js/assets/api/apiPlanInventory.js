window.apiGetPlanInventory = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        filtersFormat.start_time = filtersFormat.start_time ? formatDate(filtersFormat.start_time) : null
        filtersFormat.end_time = filtersFormat.end_time ? formatDate(filtersFormat.end_time) : null
        const response = await axios.get("/api/inventory/getPlanInventory", {
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
            data: data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}
