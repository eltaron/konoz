<?php
namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['create_user'] ?? false) && !empty($data['user_email'])) {
            $user = User::create([
                'name' => $data['name_ar'] ?? $data['name_en'] ?? 'طالب',
                'email' => $data['user_email'],
                'password' => $data['user_password'],
            ]);
            $data['user_id'] = $user->id;
        }

        unset($data['create_user'], $data['user_email'], $data['user_password']);

        return $data;
    }
}
