<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use common\models\User;
use frontend\models\SignupForm;
use frontend\tests\UnitTester;
use Yii;
use yii\mail\MessageInterface;

class SignupFormTest extends Unit
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

    public function testCorrectSignup(): void
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        $user = $model->signup();
        expect($user)->true();

        /** @var User $user */
        $user = $this->tester->grabRecord(User::class, [
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'status' => User::STATUS_INACTIVE
        ]);

        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        expect($mail)->isInstanceOf(MessageInterface::class);
        expect($mail->getTo())->hasKey('some_email@example.com');
        expect($mail->getFrom())->hasKey(Yii::$app->params['supportEmail']);
        expect($mail->getSubject())->equals('Account registration at ' . Yii::$app->name);
        expect($mail->toString())->stringContainsString($user->verification_token);
    }

    public function testNotCorrectSignup(): void
    {
        $model = new SignupForm([
            'username' => 'troy.becker',
            'email' => 'nicolas.dianna@hotmail.com',
            'password' => 'some_password',
        ]);

        expect_not($model->signup());
        expect_that($model->getErrors('username'));
        expect_that($model->getErrors('email'));

        expect($model->getFirstError('username'))
            ->equals('This username has already been taken.');
        expect($model->getFirstError('email'))
            ->equals('This email address has already been taken.');
    }
}
