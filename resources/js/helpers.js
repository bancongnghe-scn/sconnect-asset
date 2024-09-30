window.generateShortCode = async function () {
    const now = Date.now();
    const shortCode = now.toString(36);
    return shortCode.slice(-5).toUpperCase();
}

window.formData = function (data) {
    const formData = new FormData();

    for (const [key, value] of Object.entries(data)) {
        // Handle the array or file values differently if needed
        if (Array.isArray(value)) {
            value.forEach((item, index) => {
                formData.append(`${key}[${index}]`, item);
            });
        } else if (value instanceof File || value instanceof Blob) {
            formData.append(key, value);
        } else if (value !== null) {
            formData.append(key, value);
        }
    }

    return formData
}
