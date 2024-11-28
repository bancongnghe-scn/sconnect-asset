window.apiGetAppendix = async function (filters) {
    try {
        const response = await axios.get("/api/contract-appendix", {
            params: formatDateAppendix(filters)
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


window.apiRemoveAppendix = async function (id) {
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

window.apiRemoveAppendixMultiple = async function (ids) {
    try {
        const response = await axios.post("/api/delete-multiple/appendix",{ids: ids})

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

window.apiShowAppendix = async function (id) {
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

window.apiCreateAppendix = async function (dataCreate) {
    try {
        const formData = window.formData(formatDateAppendix(dataCreate))

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

window.apiUpdateAppendix = async function (dataUpdate, id) {
    try {
        const formData = window.formData(formatDateAppendix(dataUpdate))

        const response = await axios.post("/api/contract-appendix/"+id,formData)

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

function formatDateAppendix(appendix) {
    let dataFormat = JSON.parse(JSON.stringify(appendix))
    dataFormat.signing_date = dataFormat.signing_date ? window.formatDate(dataFormat.signing_date) : null
    dataFormat.from = dataFormat.from ? window.formatDate(dataFormat.from) : null
    dataFormat.to = dataFormat.to ? window.formatDate(dataFormat.to) : null

    return dataFormat
}
