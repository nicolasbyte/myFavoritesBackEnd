<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    protected const GOOGLE_RECAPTCHA_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Valida el token de reCAPTCHA con la API de Google.
     *
     * @param string|null $token
     * @return bool
     */
    public function validate(?string $token): bool
    {
        // Si no hay token, la validación falla.
        if (!$token) {
            return false;
        }

        $response = Http::asForm()->post(self::GOOGLE_RECAPTCHA_VERIFY_URL, [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $token,
        ]);

        $result = $response->json();

        // Si la petición a Google falla o la respuesta no es exitosa, falla la validación.
        if (!$response->successful() || !isset($result['success']) || !$result['success']) {
            return false;
        }

        // Si la respuesta incluye un 'score' (reCAPTCHA v3), lo validamos.
        // Si no lo incluye (como en las claves de prueba de v2), consideramos la validación exitosa.
        if (isset($result['score'])) {
            return $result['score'] > 0.5;
        }

        return true;
    }
}
