/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import 'select2/dist/css/select2.min.css'
import '@fortawesome/fontawesome-free/css/all.css'
import 'air-datepicker/air-datepicker.css';
import Alpine from 'alpinejs'

window.Alpine = Alpine
document.addEventListener('DOMContentLoaded', () => {
    $('.select2').select2()
    Alpine.start();
});

import { Toast } from 'bootstrap';
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
window.toast = toast;

import './helpers.js'



