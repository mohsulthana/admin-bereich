<?php
$app->post('/api/update/abo', 'Gemueseeggli\Controller\ApiController:updateAbo')->setName('gemueseeggli.api.updateAbo');
$app->post('/api/create/abo', 'Gemueseeggli\Controller\ApiController:createAbo')->setName('gemueseeggli.api.createAbo');
$app->post('/api/quit/abo', 'Gemueseeggli\Controller\ApiController:quitAbo')->setName('gemueseeggli.api.quitAbo');
$app->post('/api/update/addresses', 'Gemueseeggli\Controller\ApiController:updateAddresses')->setName('gemueseeggli.api.updateAddresses');
$app->get('/api/articles', 'Gemueseeggli\Controller\ApiController:getArticles')->setName('gemueseeggli.api.getArticles');
$app->get('/api/articles/{id}', 'Gemueseeggli\Controller\ApiController:getArticle')->setName('gemueseeggli.api.getArticle');
$app->get('/api/abos/{id}', 'Gemueseeggli\Controller\ApiController:getAbos')->setName('gemueseeggli.api.getAbos');
$app->get('/api/users', 'Gemueseeggli\Controller\ApiController:getUsers')->setName('gemueseeggli.api.getUsers');
$app->get('/api/bills', 'Gemueseeggli\Controller\ApiController:getBills')->setName('gemueseeggli.api.getBills');
$app->post('/api/imageupload', 'Gemueseeggli\Controller\ApiController:imageupload')->setName('gemueseeggli.api.imageupload');
$app->post('/api/contact', 'Gemueseeggli\Controller\ApiController:contact')->setName('gemueseeggli.api.contact');
$app->post('/api/create/pause', 'Gemueseeggli\Controller\ApiController:createPause')->setName('gemueseeggli.api.createPause');
$app->get('/api/delete/pause/{id}', 'Gemueseeggli\Controller\ApiController:deletePause')->setName('gemueseeggli.api.deletePause');
$app->get('/api/articletypes/{id}', 'Gemueseeggli\Controller\ApiController:getArticletypes')->setName('gemueseeggli.api.getArticletypes');