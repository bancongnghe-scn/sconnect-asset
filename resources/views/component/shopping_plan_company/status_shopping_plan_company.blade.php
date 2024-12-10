<span x-text="STATUS_SHOPPING_PLAN_COMPANY[{{$status}}]" class="p-1 border rounded"
      :class="{
            'tw-text-sky-600 tw-bg-sky-100': +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_NEW,
            'tw-text-purple-600 tw-bg-purple-100': +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_REGISTER,
            'tw-text-green-600 tw-bg-green-100': +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL
            || +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL,
            'tw-text-green-900 tw-bg-green-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_APPROVAL,
            'tw-text-red-600 tw-bg-red-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_CANCEL,
            'tw-text-amber-400 tw-bg-amber-100'  : +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE || +{{$status}} === STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC,
      }">
</span>
