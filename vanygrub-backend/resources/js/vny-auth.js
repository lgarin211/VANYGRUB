// Firebase Authentication Component for VNY Store
class VNYAuth {
    constructor() {
        this.currentUser = null;
        this.authModal = null;
        this.init();
    }

    init() {
        // Listen for auth state changes
        if (window.Firebase && window.Firebase.auth) {
            window.Firebase.auth.onAuthStateChange((user) => {
                this.currentUser = user;
                this.updateUI();
            });
        }

        // Initialize auth modal if exists
        this.initAuthModal();

        // Bind auth buttons
        this.bindAuthButtons();
    }

    initAuthModal() {
        // Create auth modal if it doesn't exist
        if (!document.getElementById('authModal')) {
            this.createAuthModal();
        }
        this.authModal = document.getElementById('authModal');
    }

    createAuthModal() {
        const modalHTML = `
            <div id="authModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold" id="authModalTitle">Masuk ke VNY Store</h3>
                        <button onclick="VNYAuth.closeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div id="authForm">
                        <!-- Login Form -->
                        <div id="loginForm">
                            <form id="loginFormElement">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="loginEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" id="loginPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors mb-3">
                                    Masuk
                                </button>
                            </form>

                            <div class="text-center mb-3">
                                <span class="text-sm text-gray-500">atau</span>
                            </div>

                            <button onclick="VNYAuth.signInWithGoogle()" class="w-full border border-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-50 transition-colors mb-4 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Masuk dengan Google
                            </button>

                            <div class="text-center">
                                <span class="text-sm text-gray-600">Belum punya akun? </span>
                                <button onclick="VNYAuth.switchToRegister()" class="text-red-600 hover:text-red-700 text-sm font-medium">Daftar</button>
                            </div>
                        </div>

                        <!-- Register Form -->
                        <div id="registerForm" class="hidden">
                            <form id="registerFormElement">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" id="registerName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="registerEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" id="registerPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" id="registerConfirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors mb-4">
                                    Daftar
                                </button>
                            </form>

                            <div class="text-center">
                                <span class="text-sm text-gray-600">Sudah punya akun? </span>
                                <button onclick="VNYAuth.switchToLogin()" class="text-red-600 hover:text-red-700 text-sm font-medium">Masuk</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }

    bindAuthButtons() {
        // Login form
        const loginForm = document.getElementById('loginFormElement');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleLogin();
            });
        }

        // Register form
        const registerForm = document.getElementById('registerFormElement');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleRegister();
            });
        }
    }

    async handleLogin() {
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        try {
            // Import signInUser dynamically
            const { signInUser } = await import('./firebase-auth.js');
            const result = await signInUser(email, password);

            if (result.success) {
                this.showMessage('Berhasil masuk!', 'success');
                this.closeModal();
            } else {
                this.showMessage(result.error, 'error');
            }
        } catch (error) {
            this.showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    }

    async handleRegister() {
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;

        if (password !== confirmPassword) {
            this.showMessage('Password tidak cocok', 'error');
            return;
        }

        try {
            const { createUser } = await import('./firebase-auth.js');
            const result = await createUser(email, password, name);

            if (result.success) {
                this.showMessage('Akun berhasil dibuat!', 'success');
                this.closeModal();
            } else {
                this.showMessage(result.error, 'error');
            }
        } catch (error) {
            this.showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    }

    async signInWithGoogle() {
        try {
            const { signInWithGoogle } = await import('./firebase-auth.js');
            const result = await signInWithGoogle();

            if (result.success) {
                this.showMessage('Berhasil masuk dengan Google!', 'success');
                this.closeModal();
            } else {
                this.showMessage(result.error, 'error');
            }
        } catch (error) {
            this.showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    }

    async signOut() {
        try {
            const { signOutUser } = await import('./firebase-auth.js');
            const result = await signOutUser();

            if (result.success) {
                this.showMessage('Berhasil keluar', 'success');
            }
        } catch (error) {
            this.showMessage('Terjadi kesalahan saat keluar', 'error');
        }
    }

    updateUI() {
        // Update auth buttons
        const loginBtn = document.querySelector('[data-auth="login"]');
        const userMenu = document.querySelector('[data-auth="user-menu"]');
        const userAvatar = document.querySelector('[data-auth="user-avatar"]');
        const userName = document.querySelector('[data-auth="user-name"]');

        if (this.currentUser) {
            // User is signed in
            if (loginBtn) loginBtn.style.display = 'none';
            if (userMenu) userMenu.style.display = 'block';
            if (userAvatar) userAvatar.src = this.currentUser.photoURL || '/images/default-avatar.png';
            if (userName) userName.textContent = this.currentUser.displayName || this.currentUser.email;
        } else {
            // User is signed out
            if (loginBtn) loginBtn.style.display = 'block';
            if (userMenu) userMenu.style.display = 'none';
        }
    }

    showModal() {
        if (this.authModal) {
            this.authModal.classList.remove('hidden');
        }
    }

    closeModal() {
        if (this.authModal) {
            this.authModal.classList.add('hidden');
        }
    }

    switchToLogin() {
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
        document.getElementById('authModalTitle').textContent = 'Masuk ke VNY Store';
    }

    switchToRegister() {
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('registerForm').classList.remove('hidden');
        document.getElementById('authModalTitle').textContent = 'Daftar VNY Store';
    }

    showMessage(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Initialize VNY Auth
const vnyAuthInstance = new VNYAuth();

// Make it globally available
window.VNYAuth = vnyAuthInstance;

export default vnyAuthInstance;
