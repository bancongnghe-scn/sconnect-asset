<span x-text="LIST_STATUS_IMPORT_WAREHOUSE[{{$status}}]" class="p-1 border rounded"
      :class="{
            'tw-text-cyan-400 tw-bg-cyan-100'  : +{{$status}} === STATUS_IMPORT_WAREHOUSE_NOT_COMPLETE,
            'tw-text-green-900 tw-bg-green-100'  :+{{$status}} === STATUS_IMPORT_WAREHOUSE_COMPLETE,
            'tw-text-red-600 tw-bg-red-100'  : +{{$status}} === STATUS_IMPORT_WAREHOUSE_CANCEL,
      }"
      @if(isset($tooltip))
          data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
      @endif
>
</span>
