import {format} from "date-fns";

window.apiGetAppendix = async function (filters) {
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
    let appendixFormat = appendix
    appendixFormat.signing_date = appendix.signing_date ? format(appendix.signing_date, 'yyyy-MM-dd') : null
    appendixFormat.from = appendix.from ? format(appendix.from, 'yyyy-MM-dd') : null
    appendixFormat.to = appendix.to ? format(appendix.to, 'yyyy-MM-dd') : null

    return appendixFormat
}
