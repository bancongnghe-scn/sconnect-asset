import {format} from "date-fns";

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

window.formatCurrencyVND = function formatCurrencyVND(number) {
    return number.toLocaleString('vi-VN') + ' vnđ';
}

window.initSelect2Modal = function initSelect2Modal(modalId) {
    $(`#${modalId}`).on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $(`#${modalId}`)
        })
    })
}

window.checkDisableSelectRow = function checkDisableSelectRow() {
    const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
    return ids.length === 0
}

window.formatDate = function formatDate(date) {
    const [day, month, year] = date.split('/').map(Number); // Tách chuỗi và chuyển đổi thành số
    date =  new Date(year, month - 1, day); // Lưu ý tháng bắt đầu từ 0
    return format(date, 'yyyy-MM-dd')
}
