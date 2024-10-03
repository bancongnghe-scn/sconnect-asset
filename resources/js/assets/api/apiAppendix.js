import {format} from "date-fns";

window.apiGetContractAppendix = async function (filters) {
    try {
        filters.signing_date = filters.signing_date ? format(filters.signing_date, 'yyyy-MM-dd') : null
        filters.from = filters.from ? format(filters.from, 'yyyy-MM-dd') : null

        const response = await axios.get("/api/contract-appendix", {
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


window.apiRemoveContract = async function (id) {
    try {
        const response = await axios.delete("/api/contract-appendix/"+id)

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

window.apiShowContract = async function (id) {
    try {
        const response = await axios.get("/api/contract-appendix/"+id)

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

window.apiCreateContract = async function (dataCreate) {
    try {
        const formData = window.formData(formatContract(dataCreate))

        const response = await axios.post("/api/contract-appendix",formData)

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

window.apiUpdateContract = async function (dataUpdate, id) {
    try {
        const formData = window.formData(formatContract(dataUpdate))

        const response = await axios.post("/api/contract-appendix/update/"+id,formData)

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

function formatContract(contract) {
    let contractFormat = contract
    contractFormat.signing_date = contract.signing_date ? format(contract.signing_date, 'yyyy-MM-dd') : null
    contractFormat.from = contract.from ? format(contract.from, 'yyyy-MM-dd') : null
    contractFormat.to = contract.to ? format(contract.to, 'yyyy-MM-dd') : null
    contractFormat.payments = contract.payments.map(payment => ({
        ...payment,
        payment_date: format(payment.payment_date, 'yyyy-MM-dd')
    }))
    return contractFormat
}
