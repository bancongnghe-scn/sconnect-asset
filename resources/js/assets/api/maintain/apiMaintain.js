window.apiGetAssetNeedMaintain = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        filtersFormat.next_maintain_start = filtersFormat.next_maintain_start ? formatDate(filtersFormat.next_maintain_start) : null
        filtersFormat.next_maintain_end = filtersFormat.next_maintain_end ? formatDate(filtersFormat.next_maintain_end) : null
        const response = await axios.get("/api/maintain/getAssetNeedMaintain", {
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

window.apiGetAssetNeedMaintainWithMonth = async function (time) {
    try {
        const response = await axios.get("/api/maintain/getAssetNeedMaintainWithMonth", {
            params: {
                time: time
            }
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


window.apiGetAssetMaintaining = async function (filters) {
    try {
        const response = await axios.get("/api/maintain/getAssetMaintaining", {
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

window.apiGetPlanMaintain = async function (filters) {
    try {
        let filtersFormat = JSON.parse(JSON.stringify(filters))
        filtersFormat.start_time = filtersFormat.start_time ? formatDate(filtersFormat.start_time) : null
        filtersFormat.end_time = filtersFormat.end_time ? formatDate(filtersFormat.end_time) : null
        const response = await axios.get("/api/maintain/getPlanMaintain", {
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

