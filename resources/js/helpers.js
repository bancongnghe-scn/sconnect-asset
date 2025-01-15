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
    if (+number === 0 || typeof number === 'undefined') {
        return 0
    }
    return number.toLocaleString('vi-VN');
}

window.initSelect2Modal = function initSelect2Modal(modalId) {
    const modalElement = $(`#${modalId}`);

    modalElement.on('shown.bs.modal', function () {
        // Khởi tạo select2 khi mở modal
        $('.select2', modalElement).select2({
            language: {
                noResults: function() {
                    return "Không tìm thấy kết quả";
                }
            },
            dropdownParent: modalElement
        });
    });

    modalElement.on('hidden.bs.modal', function () {
        // Kiểm tra nếu select2 đã được khởi tạo trước khi gọi destroy
        $('.select2', modalElement).each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        });
    });
}

window.checkDisableSelectRow = function checkDisableSelectRow() {
    const ids = Object.keys(this.selectedRow).filter(key => this.selectedRow[key] === true)
    return ids.length === 0
}

window.formatDate = function formatDate(date) {
    const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    if (regex.test(date)) {
        const [day, month, year] = date.split('/').map(Number); // Tách chuỗi và chuyển đổi thành số
        date =  new Date(year, month - 1, day); // Lưu ý tháng bắt đầu từ 0
        return format(date, 'yyyy-MM-dd')
    }

    return date
}

window.formatDateVN = function formatDateVN(date) {
    if (date === null) {
        return null
    }

    return format(date, 'dd/MM/yyyy')
}

window.convertDateString = function convertDateString(dateString) {
    const [year, month, day] = dateString.split('-')
    return new Date(year, month - 1, day)
}
