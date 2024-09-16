<div x-data="pagination" class="container d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination" x-html="renderPagination()">
            <!-- Pagination links will be inserted here by JavaScript -->
        </ul>
    </nav>
</div>

<script>
    function pagination() {
        return {
            renderPagination() {
                let paginationHtml = '';
                if (this.totalPages < 1) return paginationHtml;
                const range = [];
                const left = Math.max(1, this.currentPage - 2);
                const right = Math.min(this.totalPages, this.currentPage + 2);

                if (left > 1) {
                    range.push(1);
                    if (left > 2) range.push('...');
                }

                for (let i = left; i <= right; i++) {
                    range.push(i);
                }

                if (right < this.totalPages) {
                    if (right < this.totalPages - 1) range.push('...');
                    range.push(this.totalPages);
                }

                range.forEach(page => {
                    if (page === '...') {
                        paginationHtml += this.createEllipsis();
                    } else {
                        if (page === this.currentPage) {
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
                this.currentPage = page
                this.renderPagination
                this.$dispatch('page-change',{ page: this.currentPage })
            }
        }
    }
</script>
