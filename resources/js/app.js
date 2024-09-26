/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import '@fortawesome/fontawesome-free/css/all.css';
import Alpine from 'alpinejs'
window.Alpine = Alpine
document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});
// If you want Alpine's instance to be available globally
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');

import { Toast } from 'bootstrap';

// Toast helpers
const toast = {
    success(message) {
        this.showToast(message, 'bg-success text-white');
    },
    error(message) {
        this.showToast(message, 'bg-danger text-white');
    },
    showToast(message,className = '') {
        // Tạo HTML cho Toast
        const toastContainer = document.createElement('div');
        toastContainer.innerHTML = `
            <div class="toast ${className}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">${message}</div>
            </div>
        `;

        document.querySelector('.toast-container').append(toastContainer);

        // Khởi tạo Bootstrap toast
        const toastEl = new Toast(toastContainer.querySelector('.toast'));
        toastEl.show();

        // Xóa toast sau khi ẩn
        toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', () => {
            toastContainer.remove();
        });
    }
};

// Export để sử dụng ở những nơi khác
window.toast = toast;



