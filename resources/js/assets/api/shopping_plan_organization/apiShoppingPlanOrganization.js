window.apiGetInfoShoppingPlanOrganization = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-organization/view/"+id)

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

window.apiGetRegisterShoppingPlanOrganization = async function (id) {
    try {
        const response = await axios.get("/api/shopping-plan-organization/get-register/"+id)

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

window.apiAccountApprovalShoppingPlanOrganization = async function (ids, type) {
    try {
        const response = await axios.post("/api/shopping-plan-organization/account-approval", {ids: ids, type: type})

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

window.apiSaveReviewRegisterAsset = async function (id, registers) {
    try {
        const response = await axios.post("/api/shopping-plan-organization/review", {id: id, registers: registers})

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

