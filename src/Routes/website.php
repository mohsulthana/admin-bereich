<?php
$app->get('/', 'Gemueseeggli\Controller\PagesController:homepage')->setName('gemueseeggli.website.homepage');
$app->get('/contact', 'Gemueseeggli\Controller\PagesController:contact')->setName('gemueseeggli.website.contact');
$app->get('/abos/{id}', 'Gemueseeggli\Controller\PagesController:abodetails')->setName('gemueseeggli.website.abodetails');
$app->get('/checkout', 'Gemueseeggli\Controller\PagesController:checkout')->setName('gemueseeggli.website.checkout');
$app->post('/login', 'Gemueseeggli\Controller\AuthenticationController:login')->setName('gemueseeggli.authentication.login');
$app->get('/register', 'Gemueseeggli\Controller\AuthenticationController:register')->setName('gemueseeggli.authentication.register');
$app->post('/registration', 'Gemueseeggli\Controller\AuthenticationController:registration')->setName('gemueseeggli.authentication.registration');
$app->get('/forgotpassword[/{hash}]', 'Gemueseeggli\Controller\AuthenticationController:forgotpassword')->setName('gemueseeggli.authentication.forgotpassword');
$app->post('/resetpassword', 'Gemueseeggli\Controller\AuthenticationController:resetpassword')->setName('gemueseeggli.authentication.resetpassword');
$app->post('/setpassword', 'Gemueseeggli\Controller\AuthenticationController:setpassword')->setName('gemueseeggli.authentication.setpassword');
$app->get('/logout', 'Gemueseeggli\Controller\AuthenticationController:logout')->setName('gemueseeggli.authentication.logout');
$app->get('/noaccess', 'Gemueseeggli\Controller\PagesController:noaccess')->setName('gemueseeggli.website.noaccess');
$app->get('/myprofile', 'Gemueseeggli\Controller\PagesController:myprofile')->setName('gemueseeggli.website.myprofile');
$app->get('/myabos/abo/{id}', 'Gemueseeggli\Controller\PagesController:myabodetail')->setName('gemueseeggli.website.myabo');
$app->get('/myabos', 'Gemueseeggli\Controller\PagesController:myabos')->setName('gemueseeggli.website.myabos');
$app->get('/pause', 'Gemueseeggli\Controller\PagesController:pause')->setName('gemueseeggli.website.pause');