<?php

require  __DIR__.'/init.php';

//巴拉巴拉小魔仙世界
//onedrive::$api_url = "https://microsoftgraph.chinacloudapi.cn/v1.0";
//onedrive::$oauth_url = "https://login.partner.microsoftonline.cn/common/oauth2/v2.0";


/**
 *    程式安裝
 */
if( empty( config('refresh_token') ) ){
	route::any('/','AdminController@install');
}

/**
 *    系統後台
 */
route::group(function(){
	return ($_COOKIE['admin'] == md5(config('password').config('refresh_token')) );
},function(){
	route::get('/logout','AdminController@logout');
	route::any('/admin/','AdminController@settings');
	route::any('/admin/cache','AdminController@cache');
	route::any('/admin/show','AdminController@show');
	route::any('/admin/setpass','AdminController@setpass');
	route::any('/admin/images','AdminController@images');

	route::any('/admin/upload','UploadController@index');
	//守護進程
	route::any('/admin/upload/run','UploadController@run');
	//上傳進程
	route::post('/admin/upload/task','UploadController@task');
});
//登入
route::any('/login','AdminController@login');

//跳轉到登入
route::any('/admin/',function(){
	return view::direct(get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).'?/login');
});



define('VIEW_PATH', ROOT.'view/'.(config('style')?config('style'):'material').'/');
/**
 *    OneImg
 */
$images = config('images@base');
if( ($_COOKIE['admin'] == md5(config('password').config('refresh_token')) || $images['public']) ){
	route::any('/images','ImagesController@index');
	if($images['home']){
		route::any('/','ImagesController@index');
	}
}


/**
 *    列目錄
 */
route::any('{path:#all}','IndexController@index');

