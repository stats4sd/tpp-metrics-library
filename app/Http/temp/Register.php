<?php

namespace App\Http\Livewire;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;

class Register extends \JeffGreco13\FilamentBreezy\Http\Livewire\Auth\Register
{
    public function register()
    {

        $preparedData = $this->prepareModelData($this->form->getState());

        // for now, only allow specific individuals to register
        $allowedEmails = collect(explode(',', config('auth.allowed_users')));

        if ($allowedEmails->doesntContain($preparedData['email'])) {
            Notification::make()
                ->title('Cannot register')
                ->body('Sorry, your email is not listed in the allowed list. If you think you should be able to register, please contact support@stats4sd.org.')
                ->persistent()
                ->danger()
                ->send();

            return back();
        }

        $user = config('filament-breezy.user_model')::create($preparedData);

        event(new Registered($user));
        Filament::auth()->login($user, true);

        return redirect()->to(config('filament-breezy.registration_redirect_url'));
    }
}
