<?php

declare(strict_types=1);

namespace app\models;

use app\common\Model;
use Carbon\CarbonImmutable;
use Lcobucci\Clock\SystemClock;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $username Логин
 * @property string $name Имя
 * @property string $auth_key Ключ
 * @property string $password_hash Пароль
 * @property string|null $password_reset_token Токен для сброса пароля
 * @property string $email Email
 * @property string|null $verification_token Токен регистрации
 * @property string $status
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|null $deletedAt
 *
 */
class User extends Model implements IdentityInterface
{
    public const STATUS_BLOCK = 'block';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_NOT_ACTIVE = 'not_active';

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function rules(): array
    {
        return [
            [['username', 'password_hash', 'email', 'name'], 'required'],
            [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'status', 'name', ], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'name' => 'Имя',
            'auth_key' => 'Ключ',
            'password_hash' => 'Пароль',
            'password_reset_token' => 'Токен для сброса пароля',
            'email' => 'Email',
            'verification_token' => 'Токен регистрации',
            'status' => 'Статус',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'deletedAt' => 'Deleted At',
        ];
    }

    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = Token::TYPE_ACCESS_TOKEN): array|ActiveRecord|IdentityInterface|null
    {
        if ($type === 'yii\filters\auth\HttpBearerAuth') {
            $type = Token::TYPE_ACCESS_TOKEN;
        }
        return static::find()
            ->joinWith('tokens t', false)
            ->andWhere([
                't.token' => $token,
                't.type' => $type,
            ])
            ->andWhere([
                    '>',
                    't.expiredAt',
                    CarbonImmutable::instance(SystemClock::fromUTC()->now())->toDateTimeString(),
                ]
            )
            ->one();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findByUsernameOrEmail(string $data): ?ActiveRecord
    {
        return self::find()
            ->where([
                'or',
                ['username' => $data],
                ['email' => $data]
            ])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getTokens(): ActiveQuery
    {
        return $this->hasMany(Token::class, ['userId' => 'id']);
    }

}
