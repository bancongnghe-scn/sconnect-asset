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

window.apiCreatePlanMaintain = async function (dataCreate) {
    try {
        let dataFormat = JSON.parse(JSON.stringify(dataCreate))
        dataFormat.start_time = dataFormat.start_time ? formatDate(dataFormat.start_time) : null
        dataFormat.end_time = dataFormat.end_time ? formatDate(dataFormat.end_time) : null
        dataFormat.sent_notification = dataFormat.sent_notification ? 1 : 0
        const response = await axios.post("/api/maintain/createPlanMaintain", dataFormat)

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


window.apiGetInfoPlanMaintain = async function (id) {
    try {
        const response = await axios.get("/api/maintain/getInfoPlanMaintain/"+id)

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


window.apiCompleteAssetMaintain = async function (configs) {
    try {
        const response = await axios.post("/api/maintain/completeAssetMaintain", {
            configs: configs
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

window.apiUpdatePlanMaintain = async function (id,dataUpdate) {
    try {
        const response = await axios.post("/api/maintain/updatePlanMaintain/"+id, dataUpdate)

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


window.apiCompletePlanMaintain = async function (id) {
    try {
        const response = await axios.get("/api/maintain/completePlanMaintain/"+id)

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


window.apiDeletePlanMaintain = async function (id) {
    try {
        const response = await axios.get("/api/maintain/deletePlanMaintain/"+id)

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


