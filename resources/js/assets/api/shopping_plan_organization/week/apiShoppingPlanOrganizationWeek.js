window.apiGetShoppingPlanOrganizationWeek = async function (filters) {
    try {
        const response = await axios.get("/api/shopping-plan-organization/week/list", {
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

window.apiSentRegisterWeek = async function (id, registers = []) {
    try {
        let dataFormat = JSON.parse(JSON.stringify(registers))
        dataFormat = dataFormat.map(register => ({
            ...register,
            receiving_time: register.receiving_time ? window.formatDate(register.receiving_time) : null
        }))
        const response = await axios.post("/api/shopping-plan-organization/week/register",{
            shopping_plan_organization_id: id,
            registers: [{'assets' : dataFormat}]
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