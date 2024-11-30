@extends('layouts.app',[
    'title' => 'Kế hoạch mua sắm năm'
])

@section('content')
    <div x-data="listShoppingPlan">
        <div class="d-flex tw-gap-x-4 mb-3">
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.company ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('company')"
            >
                Kế hoạch công ty
            </a>
            <a href="#" class="tw-no-underline hover:tw-text-green-500"
               :class="activeLink.organization ? 'active-link' : 'inactive-link'"
               @click="handleShowActive('organization')"
            >
                Kế hoạch đơn vị
            </a>
        </div>

        <div x-show="activeLink.company">
            @include('component.shopping_plan_company.quarter.content_list')
        </div>
        <div x-show="activeLink.organization">
            @include('component.shopping_plan_organization.quarter.content_list')
        </div>
    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('listShoppingPlan', () => ({
                activeLink: {
                    company: true,
                    organization: false
                },

                //method
                handleShowActive(active) {
                    for (const activeKey in this.activeLink) {
                        this.activeLink[activeKey] = false
                    }

                    this.activeLink[active] = true
                }
            }))
        })
    </script>
    @vite([
        'resources/js/assets/shopping_plan_organization/year/shoppingPlanOrganizationYear.js',
        'resources/js/assets/api/shopping_plan_organization/year/apiShoppingPlanOrganizationYear.js',
        'resources/js/assets/shopping_plan_company/year/shoppingPlanCompanyYear.js',
        'resources/js/assets/api/shopping_plan_company/apiShoppingPlanCompany.js',
        'resources/js/assets/api/shopping_plan_company/year/apiShoppingPlanCompanyYear.js',
        'resources/js/app/api/apiUser.js',
    ])
@endsection
