<input x-data="{
            init() {
                this.formatPriceVnd = formatCurrencyVND(this.formatPriceVnd)
            },
            formatPriceVnd: null,
            formatCurrencyVNDInput(event) {
                let input = event.target;
                let valueInput = input.value.trim(); // Xóa khoảng trắng thừa và dấu chấm

                // Nếu giá trị rỗng, không kiểm tra
                if (!valueInput) return;

                // Kiểm tra nếu có ký tự đặc biệt
                if (/[^0-9.,]/.test(valueInput)) {
                    valueInput = valueInput.replace(/[^0-9.,]/g, '')
                    input.value = valueInput
                    toast.error('Vui lòng chỉ nhập số!');
                    return;
                }

                // Định dạng lại số với dấu chấm
                valueInput = valueInput.replace(/[.,]/g, '')
                this.{{$model}} = valueInput
                valueInput = new Intl.NumberFormat('vi-VN').format(valueInput);

                // Gán lại vào ô input
                input.value = valueInput;
            }
}" class="form-control" type="text" placeholder="{{$placeholder ?? 'Nhập số'}}"
           x-model="formatPriceVnd" @input="formatCurrencyVNDInput($event)"
       @isset($disabled) disabled @endisset
>

