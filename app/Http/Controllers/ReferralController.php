<?php

namespace App\Http\Controllers;

use App\Models\User;

class ReferralController extends Controller
{
    /** Registra o consultor via cookie (30 dias) e leva ao teste. Rota: /r/{code} */
    public function capture(string $code)
    {
        $consultant = User::where('role', 'consultant')
            ->where('referral_code', $code)
            ->first();

        $response = redirect()->route('test.intro');

        if ($consultant) {
            // cookie "ref" com o id do consultor, válido por 30 dias
            $response->withCookie(cookie('ref', (string) $consultant->id, 60 * 24 * 30));
        }

        return $response;
    }
}
