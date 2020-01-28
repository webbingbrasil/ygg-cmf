<?php

namespace Ygg\Old\Fields\Formatters;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use Ygg\Old\Fields\AbstractField;
use Ygg\Old\Fields\UploadField;
use Ygg\Old\Form\Eloquent\Uploads\UploadModel;

class MarkdownFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return array|mixed
     * @throws BindingResolutionException
     */
    public function toFront(AbstractField $field, $value)
    {
        $array = [
            'text' => $value
        ];

        foreach ($this->extractEmbeddedUploads($value) as $filename) {
            $upload = $this->getUpload($filename);

            if ($upload) {
                $array['files'][] = $upload;
            }
        }

        return $array;
    }

    /**
     * @param string|array $texts
     * @return array
     */
    protected function extractEmbeddedUploads($texts)
    {
        $matches = [];

        foreach ((array)$texts as $md) {
            preg_match_all(
                '/!\[[^\]]*\]\((?<filename>.*?)(?=\'|\))(?<optionalpart>\'.*\')?\)/',
                $md, $localeMatches, PREG_SET_ORDER, 0
            );

            $matches += $localeMatches;
        }

        return collect($matches)->map(function ($match) {
            return trim($match['filename']);
        })->all();
    }

    /**
     * @param $fullFileName
     * @return array
     * @throws BindingResolutionException
     */
    protected function getUpload($fullFileName)
    {
        [$disk, $filename] = explode(':', $fullFileName);

        $model = new UploadModel([
            'file_name' => $filename,
            'disk' => $disk,
            'size' => $this->getFileSize($fullFileName)
        ]);

        return [
            'name' => $fullFileName,
            'size' => $model->size,
            'thumbnail' => $model->thumbnail(1000, 400)
        ];
    }

    /**
     * @param string $fullFileName
     * @return mixed
     */
    protected function getFileSize($fullFileName)
    {
        try {
            [$disk, $filename] = explode(':', $fullFileName);

            return Storage::disk($disk)->size($filename);

        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * @param AbstractField|UploadField $field
     * @param string                    $attribute
     * @param                           $value
     * @return mixed
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        $text = $value['text'] ?? '';

        if (isset($value['files'])) {
            $uploadFormatter = app(UploadFormatter::class);

            foreach ($value['files'] as $file) {
                // Fist we remove the disk from the file name in order to make
                // the UploadFormatter fromFront code work properly
                $originalName = $file['name'];
                if (($pos = strpos($originalName, ':')) !== false) {
                    $file['name'] = substr($originalName, $pos + 1);
                }

                $upload = $uploadFormatter
                    ->setInstanceId($this->instanceId)
                    ->fromFront($field, $attribute, $file);

                if (isset($upload['file_name'])) {
                    // New file was uploaded. We have to update
                    // the name of the file in the markdown
                    $text = str_replace(
                        '![]('.$originalName.')',
                        '![]('.$field->storageDisk().':'.$upload['file_name'].')',
                        $text
                    );

                } elseif ($upload['transformed'] ?? false) {
                    // File was pre-existing and was transformed: we must
                    // refresh all its thumbnails (meaning delete them)
                    $this->deleteThumbnails($originalName);
                }
            }
        }

        return $text;
    }

    /**
     * @param string $fullFileName
     */
    protected function deleteThumbnails($fullFileName)
    {
        list($disk, $filename) = explode(':', $fullFileName);

        (new UploadModel([
            'file_name' => $filename,
            'disk' => $disk
        ]))->deleteAllThumbnails();
    }
}
