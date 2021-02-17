<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use frontend\models\VerifyEmailForm;
use frontend\tests\UnitTester;
use yii\base\InvalidArgumentException;
use common\models\User;

class VerifyEmailFormTest extends Unit
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

    public function testVerifyWrongToken(): void
    {
        $this->tester->expectException(InvalidArgumentException::class, function () {
            new VerifyEmailForm('');
        });

        $this->tester->expectException(InvalidArgumentException::class, function () {
            new VerifyEmailForm('notexistingtoken_1391882543');
        });
    }

    public function testAlreadyActivatedToken(): void
    {
        $this->tester->expectException(InvalidArgumentException::class, function () {
            new VerifyEmailForm('already_used_token_1548675330');
        });
    }

    public function testVerifyCorrectToken(): void
    {
        $model = new VerifyEmailForm('4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330');
        $user = $model->verifyEmail();
        expect($user)->isInstanceOf(User::class);

        expect($user->username)->equals('test.test');
        expect($user->email)->equals('test@mail.com');
        expect($user->status)->equals(User::STATUS_ACTIVE);
        expect($user->validatePassword('Test1234'))->true();
    }
}
