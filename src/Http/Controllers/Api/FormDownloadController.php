<?php

namespace Ygg\Http\Controllers\Api;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Ygg\Exceptions\InvalidResourceKeyException;
use Ygg\Fields\Field;
use Ygg\Fields\Traits\FieldWithUpload;

class FormDownloadController extends ApiController
{
    /**
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * @param FilesystemManager $filesystem
     */
    public function __construct(FilesystemManager $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $resourceKey
     * @param string $instanceId
     * @param string $formUploadFieldKey
     * @return Response
     * @throws InvalidResourceKeyException
     */
    public function show(string $resourceKey, string $instanceId, string $formUploadFieldKey): Response
    {
        ygg_check_ability('view', $resourceKey, $instanceId);

        $form = $this->getFormInstance($resourceKey);

        [$disk, $path] = $this->determineDiskAndFilePath(
            request('fileName'), $instanceId, $form->findFieldByKey($formUploadFieldKey)
        );

        abort_if(!$disk->exists($path), 404, trans('ygg::errors.file_not_found'));

        return response(
            $disk->get($path), 200, [
                'Content-Type' => $disk->mimeType($path),
                'Content-Disposition' => 'attachment'
            ]
        );
    }

    /**
     * @param string          $fileName
     * @param string          $instanceId
     * @param Field|FieldWithUpload $field
     * @return array
     */
    protected function determineDiskAndFilePath(string $fileName, $instanceId, $field): array
    {
        $basePath = str_replace('{id}', $instanceId, $field->storageBasePath());
        $storageDisk = $field->storageDisk();

        if (strpos($fileName, ':') !== false) {
            // Disk name is part of the file name, as in 'local:/my/file.jpg'.
            // This is the case in markdown embedded images.
            [$storageDisk, $fileName] = explode(':', $fileName);
        }

        $disk = $this->filesystem->disk($storageDisk);

        if (Str::startsWith($fileName, $basePath)) {
            return [$disk, $fileName];
        }

        return [$disk, $basePath.'/'.$fileName];
    }
}
