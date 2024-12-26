<span x-text="LIST_STATUS_ORDER[{{$status}}]" class="p-1 border rounded"
      :class="{
            'tw-text-cyan-400 tw-bg-cyan-100'  : +{{$status}} === ORDER_STATUS_NEW,
            'tw-text-amber-400 tw-bg-amber-100'  : +{{$status}} === ORDER_STATUS_TRANSIT,
            'tw-text-green-900 tw-bg-green-100'  : [ORDER_STATUS_DELIVERED, ORDER_STATUS_WAREHOUSED].includes(+{{$status}}),
            'tw-text-red-600 tw-bg-red-100'  : +{{$status}} === ORDER_STATUS_CANCEL,
      }"
      @if(isset($tooltip))
          data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
      @endif
>
</span>
