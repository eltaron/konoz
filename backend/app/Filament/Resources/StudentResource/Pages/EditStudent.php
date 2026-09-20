<?php
namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->color('info'),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!($data['create_user'] ?? false)) {
            unset($data['create_user'], $data['user_email'], $data['user_password']);

            return $data;
        }

        $user = $this->record->user_id ? User::find($this->record->user_id) : null;
        $email = $data['user_email'] ?? null;

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $data['name_ar'] ?? $data['name_en'] ?? 'طالب',
                'email' => $email,
                'password' => $data['user_password'] ?: Str::random(12),
                'role' => 'student',
            ]);
        } elseif ($email && $user->email !== $email) {
            $user->update(['email' => $email]);
        }

        if (!empty($data['user_password'])) {
            $user->update(['password' => $data['user_password']]);
        }

        $data['user_id'] = $user->id;
        unset($data['create_user'], $data['user_email'], $data['user_password']);

        return $data;
    }
}