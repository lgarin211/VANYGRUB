// Test script untuk Homepage API
// Jalankan di terminal: node test-homepage-api.js

const API_BASE_URL = 'http://localhost:8000/api/vny/homepage';

async function testAPI() {
    console.log('🚀 Testing Homepage API endpoints...\n');

    try {
        // Test 1: Get Homepage Constants
        console.log('1️⃣ Testing GET /constants');
        const constantsResponse = await fetch(`${API_BASE_URL}/constants`);
        const constantsData = await constantsResponse.json();
        
        if (constantsData.status === 'success') {
            console.log('✅ Constants retrieved successfully');
            console.log(`   - Gallery items count: ${constantsData.data.GALLERY_ITEMS.length}`);
            console.log(`   - Categories count: ${constantsData.data.CATEGORIES.length}`);
            console.log(`   - Primary color: ${constantsData.data.COLORS.PRIMARY}`);
        } else {
            console.log('❌ Failed to get constants');
        }
        console.log('');

        // Test 2: Get Gallery Item by ID
        console.log('2️⃣ Testing GET /gallery/1');
        const galleryItemResponse = await fetch(`${API_BASE_URL}/gallery/1`);
        const galleryItemData = await galleryItemResponse.json();
        
        if (galleryItemData.status === 'success') {
            console.log('✅ Gallery item retrieved successfully');
            console.log(`   - Title: ${galleryItemData.data.title}`);
            console.log(`   - Category: ${galleryItemData.data.category}`);
        } else {
            console.log('❌ Failed to get gallery item');
        }
        console.log('');

        // Test 3: Get Gallery Items by Category
        console.log('3️⃣ Testing GET /category/Traditional Fashion');
        const categoryResponse = await fetch(`${API_BASE_URL}/category/Traditional Fashion`);
        const categoryData = await categoryResponse.json();
        
        if (categoryData.status === 'success') {
            console.log('✅ Gallery items by category retrieved successfully');
            console.log(`   - Items found: ${categoryData.count}`);
            if (categoryData.data.length > 0) {
                console.log(`   - First item: ${categoryData.data[0].title}`);
            }
        } else {
            console.log('❌ Failed to get gallery items by category');
        }
        console.log('');

        // Test 4: Get Site Configuration
        console.log('4️⃣ Testing GET /site-config');
        const configResponse = await fetch(`${API_BASE_URL}/site-config`);
        const configData = await configResponse.json();
        
        if (configData.status === 'success') {
            console.log('✅ Site configuration retrieved successfully');
            console.log(`   - Site name: ${configData.data.site_name}`);
            console.log(`   - Contact email: ${configData.data.contact.email}`);
        } else {
            console.log('❌ Failed to get site configuration');
        }
        console.log('');

        // Test 5: Test non-existent gallery item (should return 404)
        console.log('5️⃣ Testing GET /gallery/999 (should fail)');
        const notFoundResponse = await fetch(`${API_BASE_URL}/gallery/999`);
        const notFoundData = await notFoundResponse.json();
        
        if (notFoundResponse.status === 404 && notFoundData.status === 'error') {
            console.log('✅ 404 error handled correctly');
            console.log(`   - Message: ${notFoundData.message}`);
        } else {
            console.log('❌ 404 error not handled properly');
        }

        console.log('\n🎉 All tests completed!');

    } catch (error) {
        console.error('❌ Test failed with error:', error.message);
        console.log('\n💡 Make sure Laravel server is running on http://localhost:8000');
        console.log('   Run: php artisan serve');
    }
}

// Fungsi untuk menampilkan response data secara readable
function logResponse(label, data) {
    console.log(`${label}:`);
    console.log(JSON.stringify(data, null, 2));
    console.log('');
}

// Jalankan test
testAPI();