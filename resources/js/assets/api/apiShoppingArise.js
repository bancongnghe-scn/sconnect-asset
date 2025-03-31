window.apiCreateShoppingArise = async function (dataCreate) {
    try {
        const response = await axios.post("/api/shopping-arise/createShoppingArise", dataCreate)
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

window.apiDeleteShoppingArise = async function (ids) {
    try {
        const response = await axios.post("/api/shopping-arise/deleteShoppingArise", {
            id: ids
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

window.apiUpdateShoppingArise = async function (dataUpdate, id) {
    try {
        const response = await axios.post("/api/shopping-arise/updateShoppingArise/"+id, dataUpdate)
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

window.apiGetListShoppingArise = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-arise/getListShoppingArise", {
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

window.apiFindShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/findShoppingArise/"+id)
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

window.apiHrProcessingShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/hrProcessingShoppingArise/"+id)
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

window.apiSyntheticShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/syntheticShoppingArise/"+id)
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

window.apiSendApprovalShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/sendApprovalShoppingArise/"+id)
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

window.apiCompleteShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/completeShoppingArise/"+id)
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

window.apiManagerSendShoppingArise = async function (id) {
    try {
        const response = await axios.get("/api/shopping-arise/managerSendShoppingArise/"+id)
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
