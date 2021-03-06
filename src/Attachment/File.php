<?php

namespace Ygg\Attachment;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mimey\MimeTypes;
use Ygg\Attachment\Engines\Generator;
use Ygg\Attachment\Models\Attachment;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Events\UploadFileEvent;

/**
 * Class File.
 */
class File
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var Filesystem
     */
    protected $storage;

    /**
     * @var string
     */
    protected $disk;

    /**
     * @var string|null
     */
    protected $group;

    /**
     * @var Engine
     */
    protected $engine;

    /**
     * File constructor.
     *
     * @param UploadedFile $file
     * @param string       $disk
     * @param string       $group
     */
    public function __construct(UploadedFile $file, string $disk = null, string $group = null)
    {
        abort_if($file->getSize() === false, 415, 'File failed to load.');

        $this->file = $file;

        $this->disk = $disk ?? config('platform.attachment.disk', 'public');
        $this->storage = Storage::disk($this->disk);

        $generator = config('platform.attachment.generator', Generator::class);

        $this->engine = new $generator($file);
        $this->group = $group;
    }

    /**
     * @return Model|Attachment
     */
    public function load(): Model
    {
        $attachment = $this->getMatchesHash();

        if (! $this->storage->has($this->engine->path())) {
            $this->storage->makeDirectory($this->engine->path());
        }

        if ($attachment === null) {
            return $this->save();
        }

        $attachment = $attachment->replicate()->fill([
            'original_name' => $this->file->getClientOriginalName(),
            'sort'          => 0,
            'user_id'       => Auth::id(),
            'group'         => $this->group,
        ]);

        $attachment->save();

        return $attachment;
    }

    /**
     * @return Attachment|null
     */
    private function getMatchesHash()
    {
        return Dashboard::model(Attachment::class)::where('hash', $this->engine->hash())
            ->where('disk', $this->disk)
            ->first();
    }

    /**
     * @return Model|Attachment
     */
    private function save(): Model
    {
        $this->storage->putFileAs($this->engine->path(), $this->file, $this->engine->fullName(), [
            'mime_type' => $this->engine->mime(),
        ]);

        $attachment = Dashboard::model(Attachment::class)::create([
            'name'          => $this->engine->name(),
            'mime'          => $this->engine->mime(),
            'hash'          => $this->engine->hash(),
            'extension'     => $this->engine->extension(),
            'original_name' => $this->file->getClientOriginalName(),
            'size'          => $this->file->getSize(),
            'path'          => Str::finish($this->engine->path(), '/'),
            'disk'          => $this->disk,
            'group'         => $this->group,
            'user_id'       => Auth::id(),
        ]);

        event(new UploadFileEvent($attachment, $this->engine->time()));

        return $attachment;
    }
}
