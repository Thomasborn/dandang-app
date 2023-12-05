<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
// use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use HasApiTokens;
    public function register(Request $request)
    {

     
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|string', // Adjust validation rules based on your needs
                'nomor_telepon' => 'required|string', // Adjust validation rules based on your needs
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            // Create a new user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'role' => $request->role,
                'nomor_telepon' => $request->nomor_telepon,
            ]);
            $role = Role::where('name', $request->role)->first();
            if (!$role) {
                // Handle the case where the specified role doesn't exist
                $user->delete(); // Rollback the user creation
                return response()->json(['error' => 'Invalid role specified'], 422);
            }
          
            $user->assignRole($role);
            // Generate token for the registered user
            $token = $user->createToken('MyAppToken')->plainTextToken;
            // Create a new sales record
                    // Check if the assigned role contains the word 'sales'
            if(!$user->id){
                return response()->json(['error' => 'Failed to regis user'], 422);
            }
            $request->merge(['fromAuthController' => true]);
            $request->merge(['user_id' => $user->id]);
            if (Str::contains(strtolower($role->name), 'sales')) {
                $request->merge(['tipe' => $role->name]);
                $salesController = new SalesController();
                $userRole = $salesController->store($request);
            } else if (Str::contains(strtolower($role->name), 'depo')) {
                $depoController = new DepoController(); 
                $userRole =  $depoController->store($request);
            } else if (Str::contains(strtolower($role->name), 'driver')) {
                $driverController = new DriverController(); 
                $userRole =    $driverController->store($request);
            }

           
            
            return response()->json([
                'data' => $user,
                'access_token' => $token,
                'role' => $role->name,
                'token_type' => 'Bearer',
                // 'salesController' => $salesController->getSalesData()
                'detail_role' => $userRole,
            ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $allRoles = collect(['depo', 'sales', 'driver'])
                ->map(function ($role) use ($user) {
                    return $user->$role;
                })
                ->filter()
                ->values()
                ->first();
        
            $token = $user->createToken('auth_token')->plainTextToken;
        
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'allrole' => $allRoles,
                'token_type' => 'Bearer'
            ]);
        } else {
            return new AllResource(false, 'Invalid credentials', null);
        }
        
        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Tokens revoked']);
    }
}