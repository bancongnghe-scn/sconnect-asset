window.apiGetShoppingPlanOrganizationQuarter = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-organization/quarter/list", {
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

window.apiSentRegisterQuarter = async function (id, registers = []) {
    try {
        const response = await axios.post("/api/shopping-plan-organization/quarter/register",{
            shopping_plan_organization_id: id,
            registers: registers
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