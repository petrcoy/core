<?php

// only need filesystem apps
$RUNTIME_APPTYPES=array('filesystem');

// Init owncloud


OCP\JSON::checkLoggedIn();

// Load the files
$dir = isset( $_GET['dir'] ) ? $_GET['dir'] : '';
$doBreadcrumb = isset( $_GET['breadcrumb'] ) ? true : false;
$data = array();

// Make breadcrumb
if($doBreadcrumb) {
    $breadcrumb = \OCA\files_trashbin\lib\Helper::makeBreadcrumb($dir);

	$breadcrumbNav = new OCP\Template( "files_trashbin", "part.breadcrumb", "" );
	$breadcrumbNav->assign( "breadcrumb", $breadcrumb, false );
    $breadcrumbNav->assign('baseURL', OCP\Util::linkTo('files_trashbin', 'index.php') . '?dir=');
    $breadcrumbNav->assign('home', OCP\Util::linkTo('files', 'index.php'));

	$data['breadcrumb'] = $breadcrumbNav->fetchPage();
}

// make filelist
$user = \OCP\User::getUser();
$view = new OC_Filesystemview('/'.$user.'/files_trashbin/files');
$listing = \OCA\files_trashbin\lib\Helper::getTrashFiles($view, $user, $dir);
$dirlisting = $listing['dirlisting'];
$files = $listing['files'];

$encodedDir = \OCP\Util::encodePath($dir);
$list = new OCP\Template( "files_trashbin", "part.list", "" );
$list->assign( "files", $files, false );
$list->assign('baseURL', OCP\Util::linkTo('files_trashbin', 'index.php'). '?dir='.$encodedDir);
$list->assign('downloadURL', OCP\Util::linkToRoute('download', array('file' => '/')));
$list->assign('dirlisting', $dirlisting);
$list->assign('disableDownloadActions', true);
$data['files'] = $list->fetchPage();

OCP\JSON::success(array('data' => $data));

