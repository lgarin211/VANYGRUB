// Test Next.js API Integration
// Jalankan: node test-nextjs-integration.js

const API_ENDPOINTS = {
  frontend: 'http://localhost:3000',
  backend: 'http://localhost:8000/api/vny/homepage'
};

async function testIntegration() {
  console.log('🔗 Testing Next.js <-> Laravel API Integration\n');

  // Test 1: Backend API langsung
  console.log('1️⃣ Testing Backend API directly...');
  try {
    const response = await fetch(`${API_ENDPOINTS.backend}/constants`);
    const data = await response.json();
    
    if (data.status === 'success') {
      console.log('✅ Backend API working');
      console.log(`   - Gallery items: ${data.data.GALLERY_ITEMS.length}`);
      console.log(`   - Primary color: ${data.data.COLORS.PRIMARY}`);
    } else {
      console.log('❌ Backend API failed:', data.message);
    }
  } catch (error) {
    console.log('❌ Backend API error:', error.message);
    console.log('💡 Make sure Laravel is running: php artisan serve');
  }
  console.log('');

  // Test 2: Frontend API route (jika ada)
  console.log('2️⃣ Testing Frontend connection...');
  try {
    const response = await fetch(`${API_ENDPOINTS.frontend}`);
    if (response.ok) {
      console.log('✅ Next.js frontend accessible');
    } else {
      console.log('❌ Next.js frontend not accessible');
    }
  } catch (error) {
    console.log('❌ Frontend error:', error.message);
    console.log('💡 Make sure Next.js is running: npm run dev');
  }
  console.log('');

  // Test 3: CORS check
  console.log('3️⃣ Testing CORS configuration...');
  try {
    const response = await fetch(`${API_ENDPOINTS.backend}/constants`, {
      method: 'GET',
      headers: {
        'Origin': 'http://localhost:3000',
        'Content-Type': 'application/json'
      }
    });
    
    if (response.ok) {
      console.log('✅ CORS configured correctly');
    } else {
      console.log('❌ CORS issue detected');
    }
  } catch (error) {
    console.log('❌ CORS error:', error.message);
  }
  console.log('');

  // Test 4: Environment variables
  console.log('4️⃣ Checking environment setup...');
  console.log(`   - Frontend URL: ${API_ENDPOINTS.frontend}`);
  console.log(`   - Backend URL: ${API_ENDPOINTS.backend}`);
  console.log('   - Make sure .env.local has NEXT_PUBLIC_API_URL=http://localhost:8000/api/vny');
  console.log('');

  console.log('📋 Next Steps:');
  console.log('1. Start Laravel: cd VANY GROUB-backend && php artisan serve');
  console.log('2. Start Next.js: npm run dev');
  console.log('3. Visit http://localhost:3000 to see the homepage');
  console.log('4. Check browser console for any API errors');
}

testIntegration();