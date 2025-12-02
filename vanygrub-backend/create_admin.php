use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
'name' => 'VANY GROUB Admin',
'email' => 'admin@VANY GROUB.com',
'password' => Hash::make('password123'),
'email_verified_at' => now()
]);

echo "Admin user created successfully!\n";
echo "Email: admin@VANY GROUB.com\n";
echo "Password: password123\n";
