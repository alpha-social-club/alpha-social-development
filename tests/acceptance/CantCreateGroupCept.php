<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Test behaviour when user cant create a group');

//Member cannot create a group
$I->amOnPage('/');
$I->see('Login','.button');
$I->click("//a[@class='button' and text()='Login']");
$I->seeCurrentUrlEquals('/wp/wp-login.php');
$I->fillField('log','saybb');
$I->fillField('pwd','funkadelicbro87');
$I->click('Log In');
$I->seeCurrentUrlEquals('/browse/saybb/');
$I->moveMouseOver("//a[@title='My Account']");
$I->see('Group', 'li');
$I->moveMouseOver("//a[@class='ab-item' and text()='Group']");
$I->dontSee('Create My Group', '.ab-item');
$I->moveMouseOver("//a[@title='My Account']");
$I->see('Log Out','.ab-item');
$I->click("//a[@class='ab-item' and text()='Log Out']");
$I->wait(3);
//--