<span x-text="STATUS_SHOPPING_PLAN_ORGANIZATION[{{$status}}]"
      class="p-1 border rounded @if(isset($tooltip)) d-block tw-w-fit text-xs @endif"
      :class="{
          'tw-text-purple-600 tw-bg-purple-100': +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER,
          'tw-text-green-600 tw-bg-green-100': +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED
           || +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL,
          'tw-text-green-900 tw-bg-green-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED
           || +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL
           || +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL,
          'tw-text-red-600 tw-bg-red-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL
          || +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL,
           'tw-text-amber-400 tw-bg-amber-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_HR_HANDLE
           || +{{$status}} === STATUS_SHOPPING_PLAN_ORGANIZATION_HR_SYNTHETIC,
      }"
      @if(isset($tooltip))
          data-bs-toggle="tooltip" data-bs-placement="bottom" :title="{{$tooltip}}"
      @endif
></span>
