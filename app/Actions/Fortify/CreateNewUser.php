<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida y crea un nuevo usuario registrado en el sistema.
     * Siempre se registra con rol 'cliente' (NO ADMIN).
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        // 1. Unificar nombre completo si vienen firstName y lastName por separado
        if (empty($input['name'])) {
            $nombres = trim(($input['firstName'] ?? '') . ' ' . ($input['lastName'] ?? ''));
            $input['name'] = $nombres !== '' ? $nombres : 'Usuario';
        }

        // 2. Mapear confirmación de password si viene como 'confirm'
        if (empty($input['password_confirmation']) && !empty($input['confirm'])) {
            $input['password_confirmation'] = $input['confirm'];
        }

        // 3. Mapear DNI
        $dni = $input['docId'] ?? $input['dni'] ?? null;
        if ($dni !== null) {
            $input['dni'] = trim((string) $dni);
        }

        // 4. Mapear Teléfono
        $telefono = null;
        if (!empty($input['phone'])) {
            $dial = trim((string) ($input['dial'] ?? ''));
            $phone = trim((string) $input['phone']);
            $telefono = trim("{$dial} {$phone}");
        } elseif (!empty($input['telefono'])) {
            $telefono = trim((string) $input['telefono']);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'dni' => ['nullable', 'string', 'min:6', 'max:20'],
            'password' => $this->passwordRules(),
        ], [
            'dni.min' => 'El DNI o Pasaporte debe tener entre 6 y 12 caracteres.',
            'dni.max' => 'El DNI o Pasaporte no debe exceder los 20 caracteres.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'dni' => $input['dni'] ?? null,
            'telefono' => $telefono,
            'rol' => 'cliente', // SEGURIDAD: Usuario normal, NUNCA administrador
        ]);
    }
}
