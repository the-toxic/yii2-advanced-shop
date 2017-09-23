<?php

namespace shop\tests\unit\forms;

use Codeception\Test\Unit;
use shop\forms\auth\ResetPasswordForm;

class ResetPasswordFormTest extends Unit
{
    public function testCorrectToken()
    {
        $form = new ResetPasswordForm();
        $form->password = 'new-password';
        expect_that($form->validate());
    }

    public function testWrongToken()
    {
        $form = new ResetPasswordForm();
        $form->password = '';
        expect_not($form->validate());
    }
}