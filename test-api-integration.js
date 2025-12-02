// Test script untuk memastikan API integration berjalan dengan baik
// Run: node test-api-integration.js

const API_BASE_URL = 'http://127.0.0.1:8000 /api/vny';

const testEndpoints = [
  { name: 'All Data', url: '/data' },
  { name: 'Home Data', url: '/home-data' },
  { name: 'Categories', url: '/categories' },
  { name: 'Products', url: '/products' },
  { name: 'Featured Products', url: '/featured-products' },
  { name: 'Hero Sections', url: '/hero-sections' }
];

async function testEndpoint(endpoint) {
  try {
    const response = await fetch(`${API_BASE_URL}${endpoint.url}`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }
    
    const data = await response.json();
    console.log(`✅ ${endpoint.name}: OK (${JSON.stringify(data).length} chars)`);
    return true;
  } catch (error) {
    console.log(`❌ ${endpoint.name}: FAILED (${error.message})`);
    return false;
  }
}

async function runTests() {
  console.log('🧪 Testing VANY GROUB API Integration...\n');
  
  const results = await Promise.all(
    testEndpoints.map(testEndpoint)
  );
  
  const passed = results.filter(Boolean).length;
  const total = results.length;
  
  console.log(`\n📊 Test Results: ${passed}/${total} endpoints working`);
  
  if (passed === total) {
    console.log('🎉 All API endpoints are working correctly!');
    console.log('✅ Frontend can now use API data instead of local JSON files');
  } else {
    console.log('⚠️  Some endpoints failed. Check Laravel backend is running.');
  }
}

// Run tests if this script is executed directly
if (typeof window === 'undefined') {
  runTests().catch(console.error);
}

module.exports = { testEndpoint, runTests };