<?php

namespace OCA\files_trashbin\lib;

class Helper
{
    public static function getTrashFiles($view, $user, $dir){
        $result = array();
        if ($dir && $dir !== '/') {
            $dirlisting = true;
            $dirContent = $view->opendir($dir);
            $i = 0;
            while($entryName = readdir($dirContent)) {
                if (!\OC\Files\Filesystem::isIgnoredDir($entryName)) {
                    $pos = strpos($dir.'/', '/', 1);
                    $tmp = substr($dir, 0, $pos);
                    $pos = strrpos($tmp, '.d');
                    $timestamp = substr($tmp, $pos+2);
                    $result[] = array(
                            'id' => $entryName,
                            'timestamp' => $timestamp,
                            'mime' =>  $view->getMimeType($dir.'/'.$entryName),
                            'type' => $view->is_dir($dir.'/'.$entryName) ? 'dir' : 'file',
                            'location' => $dir,
                            );
                }
            }
            closedir($dirContent);

        } else {
            $dirlisting = false;
            $query = \OC_DB::prepare('SELECT `id`,`location`,`timestamp`,`type`,`mime` FROM `*PREFIX*files_trash` WHERE `user` = ?');
            $result = $query->execute(array($user))->fetchAll();
        }

        $files = array();
        foreach ($result as $r) {
            $i = array();
            $i['name'] = $r['id'];
            $i['date'] = \OCP\Util::formatDate($r['timestamp']);
            $i['timestamp'] = $r['timestamp'];
            $i['mimetype'] = $r['mime'];
            $i['type'] = $r['type'];
            if ($i['type'] === 'file') {
                $fileinfo = pathinfo($r['id']);
                $i['basename'] = $fileinfo['filename'];
                $i['extension'] = isset($fileinfo['extension']) ? ('.'.$fileinfo['extension']) : '';
            }
            $i['directory'] = $r['location'];
            if ($i['directory'] === '/') {
                $i['directory'] = '';
            }
            $i['permissions'] = \OCP\PERMISSION_READ;
            $files[] = $i;
        }

        usort($files, array('\OCA\files\lib\Helper', 'fileCmp'));

        return array(
            'dirlisting' => $dirlisting,
            'files' => $files
        );
    }

    public static function makeBreadcrumb($dir){
        // Make breadcrumb
        $pathtohere = '';
        $breadcrumb = array();
        foreach (explode('/', $dir) as $i) {
            if ($i !== '') {
                if ( preg_match('/^(.+)\.d[0-9]+$/', $i, $match) ) {
                    $name = $match[1];
                } else {
                    $name = $i;
                }
                $pathtohere .= '/' . $i;
                $breadcrumb[] = array('dir' => $pathtohere, 'name' => $name);
            }
        }
        return $breadcrumb;
    }
}
