<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Route Admin
$route['index-admin'] = 'admin/index';
$route['manajemen-user'] = 'admin/manajemenUser';
$route['config'] = 'admin/konfigurasiAplikasi';
$route['unapproved'] = 'admin/unapprovedLogbook';
$route['f-unapproved']['get'] = 'admin/filteredUnapproved';
$route['approved'] = 'admin/approvedLogbook';
$route['f-approved']['get'] = 'admin/filteredApproved';
$route['log-activity'] = 'admin/logActivity';
$route['get-log-activity'] = 'admin/getLogActivity';
$route['config'] = 'admin/configMenu';
$route['config-detail']['get'] = 'admin/getConfigDetail';
$route['update-config'] = 'admin/updateConfigDetail';

// Route Change Password
$route['change-password'] = 'users/validateChangePass';

// Route Upload Foto
$route['upload-photo'] = 'users/doUploadProfile';

// Route Pelaksana
$route['index-pelaksana'] = 'pelaksana/index';

// Route Pejabat
$route['index-pejabat'] = 'pejabat/index';

// Route Kontrak Kinerja
$route['kontrak-kinerja'] = 'kontrakkinerja/index';
$route['save-kontrak'] = 'kontrakkinerja/addKontrakKinerja';
$route['delete-kontrak/(:any)'] = 'kontrakkinerja/deleteKontrak/$1';
$route['update-kontrak'] = 'kontrakkinerja/updateKontrak';

// Route IKU
$route['indikator-kinerja-utama'] = 'iku/index';
$route['save-iku'] = 'iku/createIKU';
$route['delete-iku/(:any)'] = 'iku/deleteIKU/$1';
$route['update-iku'] = 'iku/updateIKU';
$route['addendum-iku'] = 'iku/addendumIKU';
$route['ref-bulan'] = 'iku/refBulan';

// Route Logbook
$route['save-logbook'] = 'logbook/createLogbook';
$route['delete-logbook/(:any)'] = 'logbook/deleteLogbook/$1';
$route['update-logbook'] = 'logbook/updateLogbook';
$route['send-logbook'] = 'logbook/kirimLogbookKeAtasan/';

// Route Approval Atasan
$route['approval-atasan'] = 'pejabat/approvalAtasan';
$route['approve-kontrak-kinerja'] = 'pejabat/approveKontrakKinerja';
$route['reject-kontrak-kinerja'] = 'pejabat/rejectKontrakKinerja';
$route['approve-iku'] = 'pejabat/approveIKU';
$route['reject-iku'] = 'pejabat/rejectIKU';
$route['approve-logbook'] = 'pejabat/approveLogbook';
$route['reject-logbook'] = 'pejabat/rejectLogbook';
