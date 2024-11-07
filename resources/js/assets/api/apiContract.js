window.apiGetContract = async function (filters) {
    try {
        let dataFormat = JSON.parse(JSON.stringify(filters))
        dataFormat.signing_date = dataFormat.signing_date ? window.formatDate(dataFormat.signing_date) : null
        dataFormat.from = dataFormat.from ? window.formatDate(dataFormat.from) : null
        const response = await axios.get("/api/contract", {
            params: dataFormat
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
        const formData = window.formData(formatContract(dataCreate))
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
    dataFormat.signing_date = dataFormat.signing_date ? window.formatDate(dataFormat.signing_date) : null
    dataFormat.from = dataFormat.from ? window.formatDate(dataFormat.from) : null
    dataFormat.to = dataFormat.to ? window.formatDate(dataFormat.to) : null
    dataFormat.payments = dataFormat.payments.map(payment => ({
        ...payment,
        payment_date: payment.payment_date ? window.formatDate(payment.payment_date) : null
    }))
    return dataFormat
}
