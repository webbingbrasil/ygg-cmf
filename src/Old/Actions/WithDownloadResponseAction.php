<?php

namespace Ygg\Old\Actions;

trait WithDownloadResponseAction
{
    /**
     * @param string $filePath
     * @param null   $fileName
     * @param bool   $shouldDelete
     * @param null   $diskName
     * @return array
     */
    protected function download(string $filePath, $fileName = null, $shouldDelete = false, $diskName = null): array
    {
        return [
            'action' => 'download',
            'file' => $filePath,
            'disk' => $diskName,
            'name' => $fileName,
            'shouldDelete' => $shouldDelete
        ];
    }
}