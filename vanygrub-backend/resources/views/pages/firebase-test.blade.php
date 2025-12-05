<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Integration Test - VNY Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">🔥 Firebase Integration Test</h1>

            <!-- Firebase Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Firebase Connection Status</h2>
                <div id="firebaseStatus" class="p-4 rounded-lg bg-gray-100">
                    <span class="text-gray-600">🔄 Checking Firebase connection...</span>
                </div>
            </div>

            <!-- Authentication Test -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Authentication Test</h2>

                <div id="authStatus" class="mb-4 p-4 rounded-lg bg-gray-100">
                    <span class="text-gray-600">👤 Not logged in</span>
                </div>

                <div class="space-y-3">
                    <button onclick="testLogin()" class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                        🚪 Show Login Modal
                    </button>

                    <button onclick="testGoogleLogin()" class="w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                        🔍 Test Google Sign-In
                    </button>

                    <button onclick="testLogout()" class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors">
                        🚪 Sign Out
                    </button>
                </div>
            </div>

            <!-- Firestore Test -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Firestore Database Test</h2>

                <div class="space-y-3">
                    <button onclick="testAddWishlist()" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        ❤️ Test Add to Wishlist
                    </button>

                    <button onclick="testGetWishlist()" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                        📋 Get Wishlist
                    </button>

                    <button onclick="testAddOrder()" class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                        🛒 Test Add Order
                    </button>
                </div>

                <div id="firestoreResults" class="mt-4 p-4 rounded-lg bg-gray-100 min-h-20">
                    <span class="text-gray-600">Firestore results will appear here...</span>
                </div>
            </div>

            <!-- Storage Test -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Storage Test</h2>

                <div class="space-y-3">
                    <input type="file" id="testFile" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg">

                    <button onclick="testUpload()" class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors">
                        📤 Test File Upload
                    </button>
                </div>

                <div id="uploadProgress" class="mt-4 hidden">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-orange-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <span id="progressText" class="text-sm text-gray-600 mt-2 block">0%</span>
                </div>

                <div id="storageResults" class="mt-4 p-4 rounded-lg bg-gray-100 min-h-20">
                    <span class="text-gray-600">Storage results will appear here...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Include built assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Wait for Firebase to initialize
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                checkFirebaseStatus();
                setupAuthListener();
            }, 1000);
        });

        function checkFirebaseStatus() {
            const statusDiv = document.getElementById('firebaseStatus');

            if (window.Firebase) {
                statusDiv.innerHTML = '<span class="text-green-600">✅ Firebase is connected and ready!</span>';
                statusDiv.className = 'p-4 rounded-lg bg-green-50 border border-green-200';
            } else {
                statusDiv.innerHTML = '<span class="text-red-600">❌ Firebase not found. Please check your configuration.</span>';
                statusDiv.className = 'p-4 rounded-lg bg-red-50 border border-red-200';
            }
        }

        function setupAuthListener() {
            if (window.Firebase && window.Firebase.auth) {
                window.Firebase.auth.onAuthStateChange((user) => {
                    updateAuthStatus(user);
                });
            }
        }

        function updateAuthStatus(user) {
            const authDiv = document.getElementById('authStatus');

            if (user) {
                authDiv.innerHTML = `
                    <span class="text-green-600">✅ Logged in as: ${user.displayName || user.email}</span>
                    <br><small class="text-gray-500">UID: ${user.uid}</small>
                `;
                authDiv.className = 'mb-4 p-4 rounded-lg bg-green-50 border border-green-200';
            } else {
                authDiv.innerHTML = '<span class="text-gray-600">👤 Not logged in</span>';
                authDiv.className = 'mb-4 p-4 rounded-lg bg-gray-100';
            }
        }

        function testLogin() {
            if (window.VNYAuth) {
                window.VNYAuth.showModal();
            } else {
                alert('VNYAuth not found. Please check your Firebase integration.');
            }
        }

        async function testGoogleLogin() {
            try {
                const { signInWithGoogle } = await import('./resources/js/firebase-auth.js');
                const result = await signInWithGoogle();

                if (result.success) {
                    alert('Google sign-in successful!');
                } else {
                    alert('Google sign-in failed: ' + result.error);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function testLogout() {
            if (window.VNYAuth) {
                await window.VNYAuth.signOut();
            } else {
                alert('VNYAuth not found.');
            }
        }

        async function testAddWishlist() {
            const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
            if (!currentUser) {
                alert('Please log in first');
                return;
            }

            try {
                if (window.Firebase && window.Firebase.firestore) {
                    const result = await window.Firebase.firestore.addDocument('wishlists', {
                        userId: currentUser.uid,
                        productId: 15,
                        productName: 'Test Product',
                        testData: true
                    });

                    document.getElementById('firestoreResults').innerHTML =
                        `<span class="text-green-600">✅ Wishlist item added with ID: ${result.id}</span>`;
                } else {
                    throw new Error('Firestore not available');
                }
            } catch (error) {
                document.getElementById('firestoreResults').innerHTML =
                    `<span class="text-red-600">❌ Error: ${error.message}</span>`;
            }
        }

        async function testGetWishlist() {
            const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
            if (!currentUser) {
                alert('Please log in first');
                return;
            }

            try {
                if (window.Firebase && window.Firebase.firestore) {
                    // This would be getUserWishlist function
                    const result = await window.Firebase.firestore.getCollection('wishlists');

                    document.getElementById('firestoreResults').innerHTML =
                        `<span class="text-green-600">✅ Retrieved ${result.data ? result.data.length : 0} wishlist items</span>
                        <pre class="mt-2 text-sm text-gray-600">${JSON.stringify(result, null, 2)}</pre>`;
                } else {
                    throw new Error('Firestore not available');
                }
            } catch (error) {
                document.getElementById('firestoreResults').innerHTML =
                    `<span class="text-red-600">❌ Error: ${error.message}</span>`;
            }
        }

        async function testAddOrder() {
            const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
            if (!currentUser) {
                alert('Please log in first');
                return;
            }

            try {
                if (window.Firebase && window.Firebase.firestore) {
                    const orderData = {
                        userId: currentUser.uid,
                        items: [
                            {
                                productId: 15,
                                name: 'Test Product',
                                price: 500000,
                                quantity: 1
                            }
                        ],
                        total: 500000,
                        testOrder: true
                    };

                    const result = await window.Firebase.firestore.addDocument('orders', orderData);

                    document.getElementById('firestoreResults').innerHTML =
                        `<span class="text-green-600">✅ Order added with ID: ${result.id}</span>`;
                } else {
                    throw new Error('Firestore not available');
                }
            } catch (error) {
                document.getElementById('firestoreResults').innerHTML =
                    `<span class="text-red-600">❌ Error: ${error.message}</span>`;
            }
        }

        async function testUpload() {
            const fileInput = document.getElementById('testFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file first');
                return;
            }

            const currentUser = window.Firebase ? window.Firebase.auth.getCurrentUser() : null;
            if (!currentUser) {
                alert('Please log in first');
                return;
            }

            try {
                const progressDiv = document.getElementById('uploadProgress');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const resultsDiv = document.getElementById('storageResults');

                progressDiv.classList.remove('hidden');

                if (window.Firebase && window.Firebase.storage) {
                    const result = await window.Firebase.storage.uploadFile(
                        file,
                        `test-uploads/${currentUser.uid}/${Date.now()}_${file.name}`,
                        (progress) => {
                            progressBar.style.width = progress + '%';
                            progressText.textContent = Math.round(progress) + '%';
                        }
                    );

                    if (result.success) {
                        resultsDiv.innerHTML = `
                            <span class="text-green-600">✅ File uploaded successfully!</span>
                            <br><small class="text-gray-600">URL: ${result.url}</small>
                        `;
                    } else {
                        throw new Error(result.error);
                    }
                } else {
                    throw new Error('Firebase Storage not available');
                }
            } catch (error) {
                document.getElementById('storageResults').innerHTML =
                    `<span class="text-red-600">❌ Error: ${error.message}</span>`;
            } finally {
                setTimeout(() => {
                    document.getElementById('uploadProgress').classList.add('hidden');
                }, 2000);
            }
        }
    </script>
</body>
</html>
