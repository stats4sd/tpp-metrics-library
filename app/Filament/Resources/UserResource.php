<?php

namespace App\Filament\Resources;

use Phpsa\FilamentAuthentication\Resources\UserResource as AuthUserResource;

class UserResource extends AuthUserResource
{

    protected static ?string $recordTitleAttribute = NULL;

}