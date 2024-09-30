window.generateShortCode = async function () {
    const now = Date.now();
    const shortCode = now.toString(36);
    return shortCode.slice(-5).toUpperCase();
}

window.formData = function (data) {
    const formData = new FormData();

    Object.entries(data).forEach(([key, value]) => {
        if (Array.isArray(value) && value !== []) {
            value.forEach((item, index) => {
                if (typeof item === 'object' && !(item instanceof File)) {
                    Object.entries(item).forEach(([keyChild, valueChild]) => {
                        formData.append(`${key}[${index}][${keyChild}]`, valueChild);
                    })
                } else {
                    formData.append(`${key}[${index}]`, item);
                }
            });
        } else {
            if (value !== null) {
                formData.append(key, value);
            }
        }
    });

    return formData
}
