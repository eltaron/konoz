<?php
namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['create_user'] ?? false) && !empty($data['user_email'])) {
            $user = User::firstOrCreate(
                ['email' => $data['user_email']],
                [
                    'name' => $data['name_ar'] ?? $data['name_en'] ?? 'طالب',
                    'password' => $data['user_password'] ?: Str::random(12),
                    'role' => 'student',
                ]
            );
            $data['user_id'] = $user->id;
            $data['email'] = $data['user_email'];
        }

        unset($data['create_user'], $data['user_email'], $data['user_password']);

        return $data;
    }
}