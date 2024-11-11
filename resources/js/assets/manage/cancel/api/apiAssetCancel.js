window.apiGetAssetCancel = async function (filters) {
    try {
        filters.name_code = filters.name_code ? filters.name_code : null;

        const res = await axios.get("/api/asset/manage/asset-cancel", {
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