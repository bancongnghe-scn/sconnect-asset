const domain = import.meta.env.VITE_SC_API_DOMAIN
window.apiGetAllJob = async function () {
    try {
        const response = await axios.get('/api/service/job-title/list', {params: {}})

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

window.apiGetListJob = async function (filters) {
    try {
        const response = await axios.get('/api/service/job-title/list', {
            params: filters,
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
