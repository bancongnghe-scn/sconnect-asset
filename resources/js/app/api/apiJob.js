const VITE_SC_API_DOMAIN = import.meta.env.VITE_SC_API_DOMAIN;

window.apiGetJobs = async function (filters) {
    try {
        const url = VITE_SC_API_DOMAIN + '/api/job/getJobs'
        const response = await axios.get(url, {
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
            data: data.data
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message
        }
    }
}
