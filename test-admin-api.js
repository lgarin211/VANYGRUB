import adminApi from './lib/adminApi.js';

// Test login admin
async function testAdminAPI() {
  try {
    console.log('Testing admin login...');
    
    const loginResult = await adminApi.login('admin@VANY GROUB.com', 'admin123');
    console.log('Login successful:', loginResult);
    
    console.log('Testing get profile...');
    const profile = await adminApi.getProfile();
    console.log('Profile:', profile);
    
    console.log('Testing get categories...');
    const categories = await adminApi.getCategories();
    console.log('Categories:', categories);
    
    console.log('All API tests passed!');
  } catch (error) {
    console.error('API Test failed:', error.message);
  }
}

// Run test
testAdminAPI();