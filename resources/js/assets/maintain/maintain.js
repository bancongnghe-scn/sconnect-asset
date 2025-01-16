document.addEventListener('alpine:init', () => {
    Alpine.data('maintain', () => ({
        init() {
        },
        activeLink: {
            need_maintain: false,
            plan: true,
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
