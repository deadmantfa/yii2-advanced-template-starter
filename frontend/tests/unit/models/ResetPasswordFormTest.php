<?php

namespace frontend\tests\unit\models;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;
use frontend\tests\UnitTester;
use yii\base\InvalidArgumentException;

class ResetPasswordFormTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;


    public function _before():void
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function testResetWrongToken(): void
    {
        $this->tester->expectException(InvalidArgumentException::class, function () {
            new ResetPasswordForm('');
        });

        $this->tester->expectException(InvalidArgumentException::class, function () {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    public function testResetCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        expect_that($form->resetPassword());
    }

}
