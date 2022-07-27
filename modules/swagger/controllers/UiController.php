<?php

namespace app\modules\swagger\controllers;

use app\models\Token;
use app\models\User;
use Yii;
use yii\web\Controller;

class UiController extends Controller
{

    public function actionSwagger(?string $id = null, string $ver = 'v1'): string
    {
        Yii::$app->response->format = 'html';
        $this->layout = false;
        if (!YII_ENV_PROD) {
            if ($id) {
                $user = User::find()->where(['username' => $id])->one();
                if ($user) {
                    /** @var Token $token */
                    $token = $user->getTokens()->andWhere(['type' => Token::TYPE_ACCESS_TOKEN])
                        ->orderBy(['expiredAt' => SORT_DESC])->one();
                }
            }
            $token = $token ?? Token::find()->andWhere(['type' => Token::TYPE_ACCESS_TOKEN])
                    ->orderBy(['expiredAt' => SORT_DESC])->one();
        }

        return $this->render(
            'swagger',
            [
                'token' => $token->token ?? '',
                'ver' => $ver,
            ]
        );
    }

    public function actionTest(): string
    {
        if (!YII_ENV_PROD) {
            phpinfo();
            die();
        }
    }
}
