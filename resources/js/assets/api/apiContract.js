import {format} from "date-fns";

window.apiGetContract = async function (filters) {
    try {
        filters.signing_date = filters.signing_date ? window.formatDate(filters.signing_date) : null
        filters.from = filters.from ? window.formatDate(filters.from) : null
        const response = await axios.get("/api/contract", {
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
        const response = await axios.delete("/api/contract/"+id)

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


window.apiRemoveContractMultiple = async function (ids) {
    try {
        const response = await axios.post("/api/delete-multiple/contract",{ids: ids})

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
        const response = await axios.get("/api/contract/"+id)

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
        console.log(dataCreate)
        const formData = window.formData(formatContract(dataCreate))
        console.log(formData)
        const response = await axios.post("/api/contract",formData)

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

        console.log(formData)

        const response = await axios.post("/api/contract/"+id,formData)

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
    let dataFormat = JSON.parse(JSON.stringify(contract))
    contract.signing_date = dataFormat.signing_date ? window.formatDate(dataFormat.signing_date) : null
    contract.from = dataFormat.from ? window.formatDate(dataFormat.from) : null
    contract.to = dataFormat.to ? window.formatDate(dataFormat.to) : null
    contract.payments = dataFormat.payments.map(payment => ({
        ...payment,
        payment_date: payment.payment_date ? window.formatDate(payment.payment_date) : null
    }))
    return contract
}
