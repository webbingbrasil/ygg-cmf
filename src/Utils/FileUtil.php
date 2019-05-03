<?php

namespace Ygg\Utils;

use Illuminate\Support\Facades\Storage;

/**
 * Class FileUtil
 * @package Ygg\Utils
 */
class FileUtil
{
    /**
     * @param string $fileName
     * @param string $path
     * @param string $disk
     * @return string
     */
    public function findAvailableName(string $fileName, string $path = '', string $disk = 'local'): string
    {
        $k = 1;

        [$baseFileName, $ext] = $this->explodeExtension($fileName);

        $baseFileName = $this->normalizeName($baseFileName);
        $fileName = $baseFileName.$ext;

        while (Storage::disk($disk)->exists($path.'/'.$fileName)) {
            $fileName = $baseFileName.'-'.($k++).$ext;
        }

        return $fileName;
    }

    /**
     * @param string $fileName
     * @return array
     */
    public function explodeExtension(string $fileName): array
    {
        $ext = '';

        if (($pos = strrpos($fileName, '.')) !== false) {
            $ext = substr($fileName, $pos);
            $fileName = substr($fileName, 0, $pos);
        }

        return [$fileName, $ext];
    }

    /**
     * @param string $fileName
     * @return string|null
     */
    private function normalizeName(string $fileName): ?string
    {
        return preg_replace('#[^A-Za-z0-9-_\\.]#', '', $fileName);
    }
}
