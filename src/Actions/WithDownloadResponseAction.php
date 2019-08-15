<?php

namespace Ygg\Actions;

trait WithDownloadResponseAction
{
    /**
     * @param string $filePath
     * @param null   $fileName
     * @param null   $diskName
     * @return array
     */
    protected function download(string $filePath, $fileName = null, $diskName = null): array
    {
        return [
            'action' => 'download',
            'file' => $filePath,
            'disk' => $diskName,
            'name' => $fileName
        ];
    }
}