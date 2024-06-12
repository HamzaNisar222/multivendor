<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendConfirmationEmail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    public function register(Request $request){


            // Create user record
            $user = User::createUser($request->all());
            // Generate signed URL for email confirmation
            $confirmationUrl = URL::temporarySignedRoute('register.confirm', Carbon::now()->addMinutes(1), ['token' => $user->confirmation_token]);
            dispatch(new SendConfirmationEmail($user, $confirmationUrl));

             return Response::success('user created successfully. Verify your email',201);

    }

    public function confirmEmail(Request $request, $token)
    {
        // If no user found or token expired, redirect or respond accordingly
        if (!$request->hasValidSignature()) {
            return Response::error( 'Invalid or expired token.', 401);
            }
            // Find the user by confirmation token
            $user = User::where('confirmation_token', $token)->first();
        // Activate the user
        $user->status = 1; // Active
        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return Response::success("Email Verified Successfully",200);
    }

    public function login(Request $request){


        $user = User::authenticate($request->email, $request->password);

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate and store API token
        $user->api_token = Str::random(80);
        $user->token_expires_at = now()->addHour(); // Set expiration time to 1 hour from now
        $user->save();





        return response()->json(['token' => $user->api_token]);
    }



}
