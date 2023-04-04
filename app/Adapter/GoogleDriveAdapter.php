<?php

namespace Smis\Adapter;

use League\Flysystem\Config;

/**
 * Adapter used ONLY for backup
 */
class GoogleDriveAdapter extends \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter
{
    public function upload($path, $contents, Config $config)
    {
        // ignore folders for google drive adapter on backup
        return parent::upload(basename($path), $contents, $config);
    }

    public function listContents($dirname = '', $recursive = false)
    {
        $files = parent::listContents('', false);

        // fix map for backup cleanup checkings
        return array_map(function ($value) use ($dirname) {
            $value['path'] = (empty($dirname) ? "" : "$dirname/") . $value['path'];

            return $value;
        }, $files);
    }

    public function delete($path)
    {
        return parent::delete(basename($path));
    }
}
