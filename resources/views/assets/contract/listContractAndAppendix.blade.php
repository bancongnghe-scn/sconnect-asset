@extends('layouts.app',[
    'title' => 'Quản lý hợp đồng'
])

@section('content')
    <div x-data="contract_appendix">
        <div class="mb-4">
            <div class="d-flex tw-gap-x-4">
                <a href="#" class="tw-no-underline hover:tw-text-green-500"
                    :class="activeLink.contract ? 'active-link' : 'inactive-link'"
                    @click="handleShowActive('contract')"
                >
                    Hợp đồng
                </a>
                <a href="#" class="tw-no-underline hover:tw-text-green-500"
                    :class="activeLink.appendix ? 'active-link' : 'inactive-link'"
                    @click="handleShowActive('appendix')"
                >
                    Phụ lục hợp đồng
                </a>
            </div>

            <div>
                <div x-show="activeLink.contract">
                    @include('assets.contract.list')
                </div>

                <div x-show="activeLink.appendix">
                    @include('assets.contract_appendix.list')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    @vite([
        'resources/js/assets/contract.js',
        'resources/js/assets/api/apiContract.js',
        'resources/js/assets/api/apiSupplier.js',
        'resources/js/app/api/apiUser.js',
        'resources/js/assets/appendix.js',
        'resources/js/assets/api/apiAppendix.js',
    ])
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('contract_appendix', () => ({
                init() {

                },

                //data
                activeLink: {
                    contract: true,
                    appendix: false
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
@endsection
