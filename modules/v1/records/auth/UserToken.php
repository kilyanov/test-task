<?php

declare(strict_types=1);

namespace app\modules\v1\records\auth;

use app\models\Token as TokenModel;

/**
 * @OA\Schema(title="Токены пользователя")
 */
class UserToken extends TokenModel
{
    /**
     * @OA\Property(property="token", type="string", description="Токен")
     * @OA\Property(property="expiredAt", type="string", description="Вреся жизни токена")
     */
    public function fields(): array
    {
        return [
            'token',
            'expiredAt' => $this->getDateValue('expiredAt'),
        ];
    }
}