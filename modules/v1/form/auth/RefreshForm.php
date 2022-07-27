<?php

declare(strict_types=1);

namespace app\modules\v1\form\auth;

use app\models\Token;
use app\modules\v1\records\auth\TokenFull;
use Lcobucci\JWT\Token as TokenJWT;
use Yii;
use app\models\User;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * @OA\Schema(title="Форма обновления токенов доступа пользователя")
 */
class RefreshForm extends Model
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
            $this->_user = User::findIdentityByAccessToken($this->refreshToken, Token::TYPE_REFRESH_TOKEN);
        }

        return $this->_user;
    }

    /**
     * @throws \Exception
     */
    public function auth(): ?TokenFull
    {
        if (!$this->validate()) {
            return null;
        }
        $userId = $this->getUser()->id;
        $expire = Yii::$app->params['token_access_expire'];
        $expireRefresh = Yii::$app->params['token_refresh_expire'];

        /** @var Token $tokenAccess */
        /** @var Token $tokenRefresh */
        [$tokenAccess, $tokenRefresh] = Yii::$app->db->transaction(
            function() use ($userId, $expire, $expireRefresh) {

                Token::deleteAll(['userId' => $userId]);

                $tokenAccess = Token::createToken(
                    Yii::$app->jwt,
                    Token::TYPE_ACCESS_TOKEN,
                    $userId,
                    $expire
                );
                $tokenRefresh = Token::createToken(
                    Yii::$app->jwt,
                    Token::TYPE_REFRESH_TOKEN,
                    $userId,
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
    }

}
