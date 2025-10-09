<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * @var RecaptchaService
     */
    protected $recaptchaService;

    public function __construct()
    {
        $this->recaptchaService = app(RecaptchaService::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        Log::info('Validando token de reCAPTCHA:', ['token' => $value]);

        if (!$this->recaptchaService->validate($value)) {
            Log::error('Falló la validación de reCAPTCHA.');
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
