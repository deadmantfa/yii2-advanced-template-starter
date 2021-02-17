<?php

namespace frontend\tests\unit\models;


use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use frontend\models\ResendVerificationEmailForm;
use frontend\tests\UnitTester;
use Yii;
use yii\mail\MessageInterface;

class ResendVerificationEmailFormTest extends Unit
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

    public function testWrongEmailAddress(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'aaa@bbb.cc'
        ];

        expect($model->validate())->false();
        expect($model->hasErrors())->true();
        expect($model->getFirstError('email'))->equals('There is no user with this email address.');
    }

    public function testEmptyEmailAddress(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => ''
        ];

        expect($model->validate())->false();
        expect($model->hasErrors())->true();
        expect($model->getFirstError('email'))->equals('Email cannot be blank.');
    }

    public function testResendToActiveUser(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test2@mail.com'
        ];

        expect($model->validate())->false();
        expect($model->hasErrors())->true();
        expect($model->getFirstError('email'))->equals('There is no user with this email address.');
    }

    public function testSuccessfullyResend(): void
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = [
            'email' => 'test@mail.com'
        ];

        expect($model->validate())->true();
        expect($model->hasErrors())->false();

        expect($model->sendEmail())->true();
        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        expect('valid email is sent', $mail)->isInstanceOf(MessageInterface::class);
        expect($mail->getTo())->hasKey('test@mail.com');
        expect($mail->getFrom())->hasKey(Yii::$app->params['supportEmail']);
        expect($mail->getSubject())->equals('Account registration at ' . Yii::$app->name);
        expect($mail->toString())->stringContainsString('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
    }
}
