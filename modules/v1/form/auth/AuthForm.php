<?php

declare(strict_types=1);

namespace app\modules\v1\form\auth;

use app\models\Token;
use app\models\User as UserAlias;
use app\modules\v1\records\auth\TokenFull;
use app\modules\v1\records\user\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * @OA\Schema(
 *     required={"usernameEmail","password"},
 *     title="Вход пользователя в систему"
 * )
 */
class AuthForm extends Model
{
    /**
     * @OA\Property(
     *     property="usernameEmail",
     *     type="string",
     *     description="Username или email пользователя",
     * )
     */
    public string $usernameEmail;

    /**
     * @OA\Property(
     *     property="password",
     *     type="string",
     *     description="Пароль пользователя",
     * )
     */
    public string $password;

    private ?User $_user = null;

    public function rules(): array
    {
        return [
            [['usernameEmail', 'password', ], 'required',],
            [['usernameEmail', 'password',], 'string', 'max' => 255,],
            [
                ['usernameEmail', 'password',],
                'filter',
                'filter' => 'trim',
                'skipOnArray' => true,
            ],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Не корректно введён логин или пароль.');
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function login(): bool|TokenFull
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user === null) return false;
            else {
                $expire = Yii::$app->params['token_access_expire'];
                $expireRefresh = Yii::$app->params['token_refresh_expire'];

                switch ($user->status) {
                    case UserAlias::STATUS_ACTIVE:
                        /** @var Token $tokenAccess */
                        /** @var Token $tokenRefresh */
                        [$tokenAccess, $tokenRefresh] = Yii::$app->db->transaction(
                            function () use ($user, $expire, $expireRefresh) {
                                Token::deleteAll(['userId' => $user->id]);
                                $tokenAccess = Token::createToken(
                                    Yii::$app->jwt,
                                    Token::TYPE_ACCESS_TOKEN,
                                    $user->id,
                                    $expire
                                );
                                $tokenRefresh = Token::createToken(
                                    Yii::$app->jwt,
                                    Token::TYPE_REFRESH_TOKEN,
                                    $user->id,
                                    $expireRefresh,
                                    [
                                        'accessId' => $tokenAccess->id,
                                        'refresh' => 1,
                                    ]
                                );

                                if (!$tokenAccess->save()) {
                                    throw new Exception(implode(',', $tokenAccess->getFirstErrors()), 422);
                                }
                                if (!$tokenRefresh->save()) {
                                    throw new Exception(implode(',', $tokenRefresh->getFirstErrors()), 422);
                                }

                                return [$tokenAccess, $tokenRefresh];
                            }
                        );

                        return new TokenFull([
                            'accessToken' => $tokenAccess->token,
                            'accessExpire' => $tokenAccess->expiredAt,
                            'refreshToken' => $tokenRefresh->token,
                            'refreshExpire' => $tokenRefresh->expiredAt,
                        ]);
                    case UserAlias::STATUS_NOT_ACTIVE:
                        throw new Exception('Пользователь не активирован', 422);
                    case UserAlias::STATUS_BLOCK:
                        throw new Exception('Пользователь заблокирован', 422);
                }
            }
        }

        return false;
    }

    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsernameOrEmail($this->usernameEmail);
        }

        return $this->_user;
    }
}
