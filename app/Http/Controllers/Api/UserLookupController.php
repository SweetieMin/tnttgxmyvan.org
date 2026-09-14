<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserLookupController extends Controller
{
    public function show(string $account_code): JsonResponse
    {
        $user = User::query()
            ->where('account_code', $account_code)
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'holy_name' => $user->holyName,
                'name' => $user->lastName . ' ' . $user->name,
                'email' => $user->email,
                'username' => $user->account_code,
                'birthday' => $user->birthday ?? null,
                'phone' => $user->phone ?? null,
            ],
        ]);
    }
}
