<?php

namespace Ygg\Platform\Events;

use Illuminate\Queue\SerializesModels;
use Ygg\Attachment\Models\Attachment;

/**
 * Class UploadFileEvent.
 */
class UploadFileEvent
{
    use SerializesModels;

    /**
     * @var Attachment
     */
    public $attachment;

    /**
     * @var int
     */
    public $time;

    /**
     * ImageAttachment constructor.
     *
     * @param Attachment $attachment
     * @param int        $time
     */
    public function __construct(Attachment $attachment, int $time)
    {
        $this->attachment = $attachment;
        $this->time = $time;
    }
}
