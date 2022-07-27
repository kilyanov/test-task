<?php

declare(strict_types=1);

namespace app\modules\v1\form\auth;

use app\models\Token;
use Lcobucci\JWT\Token as TokenJWT;
use Yii;
use app\models\User;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * @OA\Schema(title="Форма для удаления токенов")
 */
class LogoutForm extends Model
{
    /**
     * @OA\Property(
     *     property="refreshToken",
     *     type="string",
     *     description="refresh token of User",
     *     title="refreshToken",
     * )
     */
    public ?string $refreshToken = '';

    private ?User $_user = null;
    private ?TokenJWT $_token;

    public function rules(): array
    {
        return [
            ['refreshToken', 'required'],
            ['refreshToken', 'validateToken'],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!Yii::$app->jwt->validate($this->refreshToken)
                || $this->getToken()->claims()->get('refresh') !== 1
                || !$this->getUser()
            ) {
                $this->addError($attribute, 'Incorrect refreshToken.');
            }
        }
    }

    /**
     * @return TokenJWT
     * @throws InvalidConfigException
     */
    protected function getToken(): TokenJWT
    {
        if (!isset($this->_token)) {
            $this->_token = Yii::$app->jwt->parse($this->refreshToken);
        }

        return $this->_token;
    }

    protected function getUser(): ?User
    {
        if (!isset($this->_user)) {
            $this->_user = User::findIdentityByAccessToken(
                $this->refreshToken,
                Token::TYPE_REFRESH_TOKEN
            );
        }

        return $this->_user;
    }

    public function logout(): bool
    {
        if ($this->validate()) {
            $userId = $this->getUser()->id;
            Token::deleteAll(['userId' => $userId]);

            return true;
        }

        return false;
    }

}
