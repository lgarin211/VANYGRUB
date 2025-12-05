import './bootstrap';
import './firebase';
import './vny-auth';

// Import Firebase utilities
import { onAuthStateChange, getCurrentUser } from './firebase-auth';
import { getCollection, addDocument } from './firebase-firestore';
import { uploadFile, validateImageFile } from './firebase-storage';

// Make Firebase utilities available globally
window.Firebase = {
    auth: {
        onAuthStateChange,
        getCurrentUser
    },
    firestore: {
        getCollection,
        addDocument
    },
    storage: {
        uploadFile,
        validateImageFile
    }
};

// VNY Store JavaScript utilities
window.VNY = {
    // Smooth scroll utility
    scrollTo: function(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    },

    // Show/hide loading states
    showLoading: function(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            element.classList.add('opacity-50', 'pointer-events-none');
        }
    },

    hideLoading: function(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            element.classList.remove('opacity-50', 'pointer-events-none');
        }
    },

    // Notification helper
    notify: function(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        // Add to DOM
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
};

// Initialize AOS if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
});
