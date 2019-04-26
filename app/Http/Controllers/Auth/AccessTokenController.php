<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Psr\Http\Message\ServerRequestInterface;
use Response;

class AccessTokenController extends \Laravel\Passport\Http\Controllers\AccessTokenController
{

    public function issueToken(ServerRequestInterface $request)
    {

        $username = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];

        $user = User::where('email', $username)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validCredentials = Hash::check($password, $user->getAuthPassword());

        if (!$validCredentials) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $tokenResult = $user->first()->createToken('Access API');

        $user->update(['api_token' => $tokenResult->accessToken]);

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
        ]);

    }

}
