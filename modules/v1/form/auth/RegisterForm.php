<?php

declare(strict_types=1);

namespace app\modules\v1\form\auth;

use app\common\rbac\CollectionRolls;
use app\models\User as UserAlias;
use app\modules\v1\records\user\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @OA\Schema(
 *     title="Регистрация пользователя в системе"
 * )
 */
class RegisterForm extends Model
{

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Имя  пользователя",
     * )
     */
    public string $name;

    /**
     * @OA\Property(
     *     property="username",
     *     type="string",
     *     description="Логин пользователя",
     * )
     */
    public string $username;

    /**
     * @OA\Property(
     *     property="password",
     *     type="string",
     *     description="Пароль пользователя",
     * )
     */
    public string $password;

    /**
     * @OA\Property(
     *     property="passwordRepeat",
     *     type="string",
     *     description="Подтверждение пароля",
     * )
     */
    public string $passwordRepeat;

    /**
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     description="Email пользователя",
     * )
     */
    public string $email;


    public function rules(): array
    {
        return [
            [['username', 'password', 'email', 'name', ], 'required',],
            [['username', 'password', 'email',], 'string', 'max' => 255,],
            [
                ['username', 'email', 'password', 'passwordRepeat',],
                'filter',
                'filter' => 'trim',
                'skipOnArray' => true,
            ],
            ['email', 'email',],
            [
                'passwordRepeat',
                'compare',
                'compareAttribute' => 'password',
                'message' => 'Пароли не совпадают',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
            'username' => 'Логин',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
            'email' => 'Email',
        ];
    }

    public function register(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User(
                ArrayHelper::merge(
                    $this->getAttributes(['username', 'email', 'name',]),
                    [
                        'status' => UserAlias::NOT_ACTIVE,
                        'verification_token' => Yii::$app->security->generateRandomString()
                    ]
                )
            );
            $user->generateAuthKey();
            $user->setPassword($this->password);
            $auth = Yii::$app->authManager;

            if ($user->save() && $this->sendEmail($user->verification_token)) {
                $transaction->commit();
                $role = $auth->getRole(CollectionRolls::ROLE_USER);
                $auth->assign($role, $user->id);

                return true;
            } else {
                $transaction->rollBack();
                $this->addError('username', implode(',', $user->getFirstErrors()));

                return false;
            }
        } catch (\Exception $exception) {
            $transaction->rollBack();
            $this->addError('username', $exception->getMessage());

            return false;
        }
    }

    public function sendEmail(string $verification_token): bool
    {
        if ($this->validate()) {
            $compose = Yii::$app->mailer->compose();
            $content = 'Код подтверждения учетной записи: ' . $verification_token;
            $compose->setTo($this->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->username])
                ->setSubject('Подтверждение учетной записи')
                ->setTextBody($content)
                ->send();

            return true;
        }

        return false;
    }

}
