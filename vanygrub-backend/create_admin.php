use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
'name' => 'VanyGrub Admin',
'email' => 'admin@vanygrub.com',
'password' => Hash::make('password123'),
'email_verified_at' => now()
]);

echo "Admin user created successfully!\n";
echo "Email: admin@vanygrub.com\n";
echo "Password: password123\n";
