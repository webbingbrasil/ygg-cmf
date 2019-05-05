<?php

namespace Ygg\Http\Controllers\Api;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Ygg\Utils\FileUtil;

/**
 * Class FormUploadController
 * @package Ygg\Http\Controllers\Api
 */
class FormUploadController extends Controller
{

    /**
     * @param FileUtil $fileUtil
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function store(FileUtil $fileUtil): JsonResponse
    {
        $file = request()->file('file');

        if (!$file) {
            throw new FileNotFoundException('File not specified');
        }

        $baseDir = config('ygg.uploads.tmp_dir', 'tmp');

        $filename = $fileUtil->findAvailableName(
            $file->getClientOriginalName(), $baseDir
        );

        $file->storeAs($baseDir, $filename, 'local');

        return response()->json([
            'name' => $filename
        ]);
    }
}
