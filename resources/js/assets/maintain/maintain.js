document.addEventListener('alpine:init', () => {
    Alpine.data('maintain', () => ({
        init() {
        },
        activeLink: {
            need_maintain: true,
            plan: false,
            maintaining: false
        },

        handleShowActive(active) {
            for (const activeKey in this.activeLink) {
                this.activeLink[activeKey] = false
            }

            this.activeLink[active] = true
        }

    }));
});
