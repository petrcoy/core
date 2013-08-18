<?php

// Check if we are a user
OCP\User::checkLoggedIn();

OCP\App::setActiveNavigationEntry('files_index');

OCP\Util::addScript('files_trashbin', 'trash');
OCP\Util::addScript('files_trashbin', 'disableDefaultActions');
OCP\Util::addScript('files', 'fileactions');
$tmpl = new OCP\Template('files_trashbin', 'index', 'user');

$user = \OCP\User::getUser();
$view = new OC_Filesystemview('/'.$user.'/files_trashbin/files');

OCP\Util::addStyle('files', 'files');
OCP\Util::addScript('files', 'filelist');
// filelist overrides
OCP\Util::addScript('files_trashbin', 'filelist');

$dir = isset($_GET['dir']) ? stripslashes($_GET['dir']) : '';

$listing = \OCA\files_trashbin\lib\Helper::getTrashFiles($view, $user, $dir);
$dirlisting = $listing['dirlisting'];
$files = $listing['files'];

$breadcrumb = \OCA\files_trashbin\lib\Helper::makeBreadcrumb($dir);

$breadcrumbNav = new OCP\Template('files_trashbin', 'part.breadcrumb', '');
$breadcrumbNav->assign('breadcrumb', $breadcrumb);
$breadcrumbNav->assign('baseURL', OCP\Util::linkTo('files_trashbin', 'index.php') . '?dir=');
$breadcrumbNav->assign('home', OCP\Util::linkTo('files', 'index.php'));

$list = new OCP\Template('files_trashbin', 'part.list', '');
$list->assign('files', $files);

$encodedDir = \OCP\Util::encodePath($dir);
$list->assign('baseURL', OCP\Util::linkTo('files_trashbin', 'index.php'). '?dir='.$encodedDir);
$list->assign('downloadURL', OCP\Util::linkTo('files_trashbin', 'download.php') . '?file='.$encodedDir);
$list->assign('dirlisting', $dirlisting);
$list->assign('disableDownloadActions', true);

$tmpl->assign('dirlisting', $dirlisting);
$tmpl->assign('breadcrumb', $breadcrumbNav->fetchPage());
$tmpl->assign('fileList', $list->fetchPage());
$tmpl->assign('files', $files);
$tmpl->assign('dir', $dir);
$tmpl->assign('disableSharing', true);

$tmpl->printPage();
