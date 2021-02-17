<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\UserFixture as UserFixture;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\tests\UnitTester;
use Yii;
use yii\mail\MessageInterface;

class PasswordResetRequestFormTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;


    public function _before(): void
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testSendMessageWithWrongEmailAddress(): void
    {
        $model = new PasswordResetRequestForm();
        $model->email = 'not-existing-email@example.com';
        expect_not($model->sendEmail());
    }

    public function testNotSendEmailsToInactiveUser(): void
    {
        $user = $this->tester->grabFixture('user', 1);
        $model = new PasswordResetRequestForm();
        $model->email = $user['email'];
        expect_not($model->sendEmail());
    }

    public function testSendEmailSuccessfully(): void
    {
        $userFixture = $this->tester->grabFixture('user', 0);

        $model = new PasswordResetRequestForm();
        $model->email = $userFixture['email'];
        $user = User::findOne(['password_reset_token' => $userFixture['password_reset_token']]);

        expect_that($model->sendEmail());
        expect_that($user->password_reset_token);

        $emailMessage = $this->tester->grabLastSentEmail();
        expect('valid email is sent', $emailMessage)->isInstanceOf(MessageInterface::class);
        expect($emailMessage->getTo())->hasKey($model->email);
        expect($emailMessage->getFrom())->hasKey(Yii::$app->params['supportEmail']);
    }
}
