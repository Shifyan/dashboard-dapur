<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Setting extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.admin.pages.setting';

    protected static ?string $title = 'Pengaturan Profil';

    protected static bool $shouldRegisterNavigation = false;

    /**
     * Action: ubah username
     */
    public function editUsernameAction(): Action
    {
        return Action::make('editUsername')
            ->label('Ubah')
            ->icon('heroicon-o-pencil-square')
            ->color('primary')
            ->modalHeading('Ubah Username')
            ->modalSubmitActionLabel('Simpan')
            ->schema([
                TextInput::make('username')
                    ->label('Username Baru')
                    ->default(fn () => auth()->user()->username)
                    ->required()
                    ->unique('users', 'username', ignorable: auth()->user()),
            ])
            ->action(function (array $data): void {
                auth()->user()->update(['username' => $data['username']]);
                Notification::make()->title('Username berhasil diperbarui!')->success()->send();
            });
    }

    /**
     * Action: ubah email
     */
    public function editEmailAction(): Action
    {
        return Action::make('editEmail')
            ->label('Ubah')
            ->icon('heroicon-o-pencil-square')
            ->color('primary')
            ->modalHeading('Ubah Email')
            ->modalSubmitActionLabel('Simpan')
            ->schema([
                TextInput::make('email')
                    ->label('Email Baru')
                    ->email()
                    ->default(fn () => auth()->user()->email)
                    ->required()
                    ->unique('users', 'email', ignorable: auth()->user()),
            ])
            ->action(function (array $data): void {
                auth()->user()->update(['email' => $data['email']]);
                Notification::make()->title('Email berhasil diperbarui!')->success()->send();
            });
    }

    /**
     * Action: ubah password
     */
    public function editPasswordAction(): Action
    {
        return Action::make('editPassword')
            ->label('Ubah Password')
            ->icon('heroicon-o-lock-closed')
            ->color('warning')
            ->modalHeading('Ubah Password')
            ->modalSubmitActionLabel('Simpan Password Baru')
            ->schema([
                TextInput::make('current_password')
                    ->label('Password Saat Ini')
                    ->password()
                    ->revealable()
                    ->required()
                    ->currentPassword(),
                TextInput::make('new_password')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::default()),
                TextInput::make('new_password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('new_password'),
            ])
            ->action(function (array $data): void {
                auth()->user()->update(['password' => Hash::make($data['new_password'])]);
                Notification::make()->title('Password berhasil diperbarui!')->success()->send();
            });
    }
}
