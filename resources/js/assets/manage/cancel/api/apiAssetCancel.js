window.apiGetAssetCancel = async function (filters) {
    try {

        const res = await axios.get("/api/manage-asset-cancel", {
            params: filters
        })

        const data = res.data;
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