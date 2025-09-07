<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Shop';
$route['404_override'] = 'ErrorHandler/error_404';
$route['translate_uri_dashes'] = FALSE;

// Admin Routes - These must come BEFORE other routes
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/logout'] = 'admin/logout';
$route['admin/(.+)'] = 'admin/$1';

// API Routes
$route['api'] = 'api/index';
$route['api/health'] = 'api/health';
$route['api/status'] = 'api/status';
$route['api/log-error'] = 'api/log_error';

// User Routes
$route['user/dashboard'] = 'user/dashboard';
$route['user/profile'] = 'user/profile';
$route['user/addresses'] = 'user/addresses';
$route['user/getDashboardStats'] = 'user/getDashboardStats';
$route['user/getSavedAddresses'] = 'user/getSavedAddresses';
$route['user/getProfileData'] = 'user/getProfileData';
$route['user/updateProfile'] = 'user/updateProfile';
$route['user/changePassword'] = 'user/changePassword';
$route['user/updateNotificationPreferences'] = 'user/updateNotificationPreferences';
$route['user/uploadProfileImage'] = 'user/uploadProfileImage';

// Error Routes
$route['error/404'] = 'ErrorHandler/error_404';
$route['error/401'] = 'ErrorHandler/error_401';
$route['error/403'] = 'ErrorHandler/error_403';
$route['error/500'] = 'ErrorHandler/error_500';
$route['maintenance'] = 'ErrorHandler/maintenance';

// Grocery category routes - main categories
$route['category/fruits-vegetables'] = 'category/index/fruits-vegetables';
$route['category/dairy-bakery'] = 'category/index/dairy-bakery';
$route['category/grains-rice'] = 'category/index/grains-rice';
$route['category/spices-condiments'] = 'category/index/spices-condiments';
$route['category/snacks-beverages'] = 'category/index/snacks-beverages';
$route['category/personal-care'] = 'category/index/personal-care';
$route['category/household-items'] = 'category/index/household-items';
$route['category/meat-seafood'] = 'category/index/meat-seafood';
$route['category/oils-ghee'] = 'category/index/oils-ghee';

// Category with subcategory route
$route['category/(:any)/(:any)'] = 'category/index/$1/$2';

// Generic category route (for backward compatibility and future expansion)
$route['category/(:any)'] = 'category/index/$1';

// Product search routes
$route['product/search'] = 'product/search';
$route['product/searchSuggestions'] = 'product/searchSuggestions';
$route['product/searchFilter'] = 'product/searchFilter';
$route['product/(:any)'] = 'product/index/$1';
