<?php

declare(strict_types=1);

namespace app\modules\v1\form\application;

use app\modules\v1\records\application\ListApplication;
use Yii;
use yii\base\Model;

/**
 * @OA\Schema(title="Форма для создания ответа на зявку")
 */
class ApplicationUpdateForm extends Model
{

    private ?ListApplication $application = null;

    public function __construct(ListApplication $application, $config = [])
    {
        $this->application = $application;
        parent::__construct($config);
    }

    /**
     * @OA\Property(
     *     property="answer",
     *     type="string",
     *     description="Ответ на заявку",
     *     title="answer",
     * )
     */
    public ?string $answer = null;


    public function rules(): array
    {
        return [
            [['answer',], 'required'],
            [['answer', ], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'answer' => 'Ответ',
        ];
    }

    public function sendEmail(): bool
    {
        if ($this->validate()) {
            $compose = Yii::$app->mailer->compose();
            $content = 'Вы получили ответ на Вашу заявку: ' . $this->answer;
            $compose->setTo($this->application->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->application->email => $this->application->name])
                ->setSubject('Ответ на заявку')
                ->setTextBody($content)
                ->send();

            return true;
        }

        return false;
    }
}
