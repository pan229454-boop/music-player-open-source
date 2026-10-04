<?php
use think\facade\Route;

Route::get('favicon', 'webapi/getFavicon');  
Route::get('music/search', 'webapi/search');
Route::get('music/info', 'webapi/info');
Route::get('music/url', 'webapi/url');
Route::get('music/list', 'webapi/playlist');
Route::get('ip', 'IpLocation/findLocation');
// Bind theme/about explicitly instead of relying on unnamed PATH_INFO parameters.
Route::get('admin/system/:act', 'Admin/system')->pattern(['act' => 'theme|about'])->completeMatch();
