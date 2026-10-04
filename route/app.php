<?php
use think\facade\Route;

Route::get('favicon', 'webapi/getFavicon');  
Route::get('music/search', 'webapi/search');
Route::get('music/info', 'webapi/info');
Route::get('music/url', 'webapi/url');
Route::get('music/list', 'webapi/playlist');
Route::get('ip', 'IpLocation/findLocation');