<?php
$app->get('/admin', 'Gemueseeggli\Controller\AdminController:homepage')->setName('gemueseeggli.admin.homepage');
$app->get('/admin/articles', 'Gemueseeggli\Controller\AdminController:articles')->setName('gemueseeggli.admin.articles');
$app->get('/admin/articledetails/{id}', 'Gemueseeggli\Controller\AdminController:articledetails')->setName('gemueseeggli.admin.articledetails');
$app->post('/admin/articledetails/{id}', 'Gemueseeggli\Controller\AdminController:articledetailsSave')->setName('gemueseeggli.admin.articledetailsSave');
$app->get('/admin/abos', 'Gemueseeggli\Controller\AdminController:abos')->setName('gemueseeggli.admin.abos');
$app->get('/admin/users', 'Gemueseeggli\Controller\AdminController:users')->setName('gemueseeggli.admin.users');
$app->get('/admin/userdetails/{id}', 'Gemueseeggli\Controller\AdminController:userdetails')->setName('gemueseeggli.admin.userdetails');
$app->post('/admin/userdetails/{id}', 'Gemueseeggli\Controller\AdminController:userdetailsSave')->setName('gemueseeggli.admin.userdetailsSave');
$app->get('/admin/abodetails/{id}', 'Gemueseeggli\Controller\AdminController:abodetails')->setName('gemueseeggli.admin.abodetails');
$app->post('/admin/abodetails/{userid}/{id}', 'Gemueseeggli\Controller\AdminController:abodetailssave')->setName('gemueseeggli.admin.abodetailssave');
$app->get('/admin/deliverynotes', 'Gemueseeggli\Controller\AdminController:deliverynotes')->setName('gemueseeggli.admin.deliverynotes');
$app->get('/admin/bills', 'Gemueseeggli\Controller\AdminController:bills')->setName('gemueseeggli.admin.bills');
$app->post('/admin/create/deliverynotes', 'Gemueseeggli\Controller\AdminController:createDeliverynotes')->setName('gemueseeggli.admin.createDeliverynotes');
$app->get('/admin/articletypedetails/{id}', 'Gemueseeggli\Controller\AdminController:articletypedetails')->setName('gemueseeggli.admin.articletypedetails');
$app->post('/admin/articletypedetails/{articleId}/{id}', 'Gemueseeggli\Controller\AdminController:articletypedetailssave')->setName('gemueseeggli.admin.articletypedetailssave');