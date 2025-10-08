<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Jobs\SendPasswordResetEmailJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        // Si el usuario existe, generamos token y despachamos el Job
        if ($user) {
            // Generar token
            $token = app('auth.password.broker')->createToken($user);

            // Construir URL de reseteo
            $frontendUrl = config('app.frontend_url');
            $resetUrl = "{$frontendUrl}/reset-password?token={$token}&email={$user->email}";

            // Despachar el Job
            SendPasswordResetEmailJob::dispatch($user, $resetUrl);
        }

        return response()->json([
            'message' => 'Si el correo electrónico existe, te enviaremos un correo para restablecer tu contraseña.'
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // El Form Request ya ha validado los datos
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        // Usamos el Password Broker para resetear la contraseña
        $status = Password::broker()->reset($credentials, function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        });

        // Verificamos el estado del reseteo
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset successfully.']);
        }

        // Si el token es inválido o ha expirado
        return response()->json(['message' => 'Invalid token or email.'], 400);
    }

    /**
     * Log the user out (Revoke the token).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();
        $token->revoke();

        // Revoke the refresh token
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update(['revoked' => true]);

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
