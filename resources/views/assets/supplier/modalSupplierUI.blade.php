<div class="modal fade" id="modalSupplierUI" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" x-text="titleAction + ' nhà cung cấp'"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        @include('assets.supplier.mainInfoSupplier')
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex tw-gap-x-4">
                                <a href="#" class="tw-no-underline hover:tw-text-green-500"
                                   :class="activeLink.payment_terms ? 'active-link' : 'inactive-link'">
                                    Điều khoản thanh toán
                                </a>
                                <a href="#" class="tw-no-underline hover:tw-text-green-500"
                                   :class="activeLink.payment_account ? 'active-link' : 'inactive-link'">
                                    Tài khoản ngân hàng
                                </a>
                            </div>
                        </div>

                        <div>
                            <div x-show="activeLink.payment_terms">
                                @include('assets.supplier.paymentTerms')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button @click="$dispatch('save-supplier')" type="button" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</div>

<style>
    .active-link {
        color: #28a745; /* Màu xanh lá */
        font-weight: bold;
        position: relative;
    }
    .active-link::after {
        content: '';
        display: block;
        width: 100%;
        height: 2px;
        background-color: #28a745;
        position: absolute;
        bottom: -3px;
        left: 0;
    }
    .inactive-link {
        color: #6c757d; /* Màu xám */
        position: relative;
    }
    .inactive-link:hover {
        color: #28a745; /* Màu xanh lá khi hover */
        font-weight: bold;
    }
    .inactive-link:hover::after {
        content: '';
        display: block;
        width: 100%;
        height: 2px;
        background-color: #28a745;
        position: absolute;
        bottom: -3px;
        left: 0;
    }
</style>
