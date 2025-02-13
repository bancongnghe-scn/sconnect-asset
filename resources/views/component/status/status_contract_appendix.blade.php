<span
    x-text="STATUS_APPENDIX[{{$status}}]"
    x-data="{
        getStyle(status) {
            if (status === STATUS_APPENDIX_PENDING) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
                };
            } else if (status === STATUS_APPENDIX_APPROVED)
            {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FAAD14'
                };
            } else if (status === STATUS_APPENDIX_CANCEL)
            {
                return {
                    color: '#F5222D',
                    backgroundColor: '#FFF1F0',
                    border: '1px solid #F5222D'
                };
            }
            return {};
        }
    }"
    style="
        font-size: 12px;
        padding: 3px 12px 3px 12px;
        border-radius: 8px;
    "
    :style="getStyle(+{{$status}})"
    class="@if(isset($tooltip)) d-block tw-w-fit text-xs @endif"
    @if(isset($tooltip))
        data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
    @endif
>
</span>

