<div x-data="pagination" class="d-flex justify-content-between pl-3 pr-3">
    <div>
        <span x-text="'Hiển thị ' + fromMore + ' đến ' + toMore + ' trong ' + totalMore + ' bản ghi'"></span>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <span>Hiển thị</span>
        <select class="form-select w-auto" x-model="limitMore" @change="$dispatch('change-limit-more')">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span>bản ghi</span>
    </div>
</div>

<script>
    function pagination() {
        return {
            renderPagination() {
                let paginationHtml = '';
                if (this.totalPagesMore < 1) return paginationHtml;
                const range = [];
                const left = Math.max(1, this.currentPageMore - 2);
                const right = Math.min(this.totalPagesMore, this.currentPageMore + 2);

                if (left > 1) {
                    range.push(1);
                    if (left > 2) range.push('...');
                }

                for (let i = left; i <= right; i++) {
                    range.push(i);
                }

                if (right < this.totalPagesMore) {
                    if (right < this.totalPagesMore - 1) range.push('...');
                    range.push(this.totalPagesMore);
                }

                range.forEach(page => {
                    if (page === '...') {
                        paginationHtml += this.createEllipsis();
                    } else {
                        if (page === this.currentPageMore) {
                            paginationHtml += this.createActivePageItem(page);
                        } else {
                            paginationHtml += this.createPageItem(page);
                        }
                    }
                });

                return paginationHtml;
            },

            createPageItem(page) {
                return `
                        <li class="page-item">
                            <a class="page-link" @click="changePage(${page})"> ${page} </a>
                        </li>
                    `;
            },

            createActivePageItem(page) {
                return `
                        <li class="page-item active">
                            <a class="page-link" href="#"> ${page} </a>
                        </li>
                    `;
            },

            createEllipsis() {
                return `
                        <li class="page-item disabled">
                            <span class="page-link"> ... </span>
                        </li>
                    `;
            },

            changePage(page) {
                this.currentPageMore = page
                this.renderPagination
                this.$dispatch('change-page',{ page: this.currentPageMore })
            }
        }
    }
</script>
