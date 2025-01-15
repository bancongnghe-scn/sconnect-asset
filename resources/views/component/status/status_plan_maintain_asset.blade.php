<span x-text="LIST_STATUS_ASSET_MAINTAIN[{{$status}}]"
      x-data="{
        getStyle(status) {
            if (status === STATUS_ASSET_MAINTAINING) {
                return {
                    color: '#FAAD14',
                    backgroundColor: '#FFFBE6',
                    border: '1px solid #FFE58F'
                };
            }
            else if (status === STATUS_ASSET_MAINTAIN_COMPLETE) {
                return {
                    color: '#52C41A',
                    backgroundColor: '#F6FFED',
                    border: '1px solid #52C41A'
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
>
</span>
