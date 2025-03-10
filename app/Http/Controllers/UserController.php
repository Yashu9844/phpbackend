<?php
namespace App\HTTP\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller 
{
    public function updateUser(Request $requestt , $userId){

        if(Auth::id() != $userId){
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $validatedData = $request->validate([
            'password' => 'nullable|string|min:6',
            'username' => [
                'nullable',
                'string',
                'min:7',
                'max:20',
                'regex:/^[a-z0-9]+$/', // Only lowercase letters and numbers
            ],
            'email' => 'nullable|email',
            'profilePicture' => 'nullable|string'
        ]);


   $user = User::findOrFail($userId);

   if($request->filled('password')){
       $validatedData['password'] = Hash::make($request->password);
   }

   $user->update($validatedData);

   return response()->json($user->only(['id', 'username', 'email', 'profilePicture']), 200);
    }



    public function deleteUser($id){
        // $user = Auth::user();

        // if(!$user || !$user->is_admin && $user->id != $id){
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $id = (int) $id;
$userToDelete = User::find($id);

if(!$userToDelete){
    return response()->json(['message'=>'user not found'], 404);
}
$userToDelete->delete();

return response()->json(['message'=>'User has deleted'],200);

    }


    public function signout(Request $request) {
        $user = Auth::user();
    
        if ($user) {
            $request->user()->tokens()->delete(); // Revoke all tokens
            return response()->json(['message' => 'User has been signed out'], 200);
        }
    
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function getUsers(Request $request) {
        // Check if user is an admin
        // $user = Auth::user();
        // if (!$user || !$user->is_admin) {
        //     return response()->json(['message' => 'You are not allowed to see all users'], 403);
        // }
    
        // Pagination parameters
        $startIndex = intval($request->query('startIndex', 0));
        $limit = intval($request->query('limit', 9));
        $sortDirection = $request->query('sort', 'desc') === 'asc' ? 'asc' : 'desc';
    
        // Get users with sorting and pagination
        $users = User::orderBy('created_at', $sortDirection)
            ->skip($startIndex)
            ->take($limit)
            ->get();
    
        // Remove password field before sending response
        $usersWithoutPassword = $users->map(function ($user) {
            return collect($user)->except(['password']);
        });
    
        // Get total user count
        $totalUsers = User::count();
    
        // Calculate last month's users
        $oneMonthAgo = now()->subMonth();
        $lastMonthUsers = User::where('created_at', '>=', $oneMonthAgo)->count();
    
        return response()->json([
            'users' => $usersWithoutPassword,
            'totalUsers' => $totalUsers,
            'lastMonthUsers' => $lastMonthUsers
        ], 200);
    }

public function getUser($id){


    $user = User::find($id);

    if(!$user){
        return response()->json(['message'=>'User not found'],404);

    }

    return response()->json(['user'=>$user->makeHidden(['password'])]);
}

}