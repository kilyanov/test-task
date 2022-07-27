<?php

declare(strict_types=1);

namespace app\modules\v1\form\auth;

use app\common\Model as ModelAlias;
use Yii;
use app\models\PhoneCheck;
use app\modules\v1\records\user\User;
use yii\base\Exception;
use yii\base\Model;

/**
 * @OA\Schema(title="Подтверждение регистрации нового пользователя")
 */
class ConfirmForm extends Model
{

    /**
     * @OA\Property(
     *     property="code",
     *     type="string",
     *     description="Код подтверждения",
     * )
     */
    public ?string $code = null;

    public function rules(): array
    {
        return [
            [['code',], 'required',],
            [['code',], 'string',],
            [
                ['code',],
                'filter',
                'filter' => 'trim',
                'skipOnArray' => true,
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'code' => 'Код',
        ];
    }

    public function verification(): bool
    {
        $user = User::findOne([
            'status' => ModelAlias::NOT_ACTIVE,
            'verification_token' => $this->code,
        ]);
        if ($user instanceof User) {
            $user->status = ModelAlias::ACTIVE;
            return $user->save();
        }
        else{
            $this->addError('code', 'Код указан не верно');
            return false;
        }
    }

}
