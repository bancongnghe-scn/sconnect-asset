import {format} from "date-fns";

window.apiGetAssetLost = async function (filters) {
    try {

        const res = await axios.get("/api/manage-asset-lost/list", {
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

window.apiShowAssetLost = async function (id) {
    try {
        const response = await axios.get("/api/manage-asset-lost/"+id)

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

window.apiRevertAsset = async function (dataUpdate) {
    try {
        const response = await axios.post("/api/manage-asset-lost/update",dataUpdate)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message,
            text: error
        }
    }
}

window.apiCanceltAsset = async function (dataCancel) {
    try {
        const response = await axios.post("/api/manage-asset-lost/update",dataCancel)

        const data = response.data;
        if (!data.success) {
            return {
                success: false,
                message: data.message
            }
        }

        return {
            success: true,
        }
    } catch (error) {
        return {
            success: false,
            message: error?.response?.data?.message || error?.message,
            text: error
        }
    }
}