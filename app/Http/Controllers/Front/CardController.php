<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Quét QR token_v2: xem người này đã được làm thẻ (has_card) chưa.
     */
    public function cardView($token)
    {
        SeoService::setDefaultSeo('Làm thẻ');

        $user = $this->findByTokenV2($token);

        return view('front.card', [
            'user' => $user,
            'pageTitle' => 'Làm thẻ',
        ]);
    }

    /**
     * Xác nhận đã làm thẻ: cập nhật has_card = 1.
     */
    public function confirmCard($token)
    {
        $user = $this->findByTokenV2($token);

        if ($user->has_card) {
            return redirect()
                ->route('card_view', ['token' => $token])
                ->with('card_info', 'Người này đã được xác nhận làm thẻ trước đó.');
        }

        // Chỉ cập nhật cột has_card, không đụng các cột khác
        DB::table('users')
            ->where('id', $user->id)
            ->where('has_card', 0)
            ->update(['has_card' => 1]);

        return redirect()
            ->route('card_view', ['token' => $token])
            ->with('card_success', 'Đã xác nhận làm thẻ cho ' . $user->SimpleName . '.');
    }

    private function findByTokenV2($token): User
    {
        return User::with(['roles', 'courses', 'sectors', 'studentParent'])
            ->where('token_v2', $token)
            ->firstOrFail();
    }
}
