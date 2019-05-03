<?php

namespace Ygg\Utils;

/**
 * Class Notification
 * @package Ygg\Utils
 */
class Notification
{
    /**
     * @var string
     */
    protected $id;

    /**
     * YggNotification constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->id = uniqid('ygg', true);
        $notifications = session('ygg_notifications') ?? [];

        $notifications[$this->id] = [
            'title' => $title,
            'level' => 'info',
            'message' => null,
            'autoHide' => true
        ];

        session()->put('ygg_notifications', $notifications);
    }

    /**
     * @param string $detail
     * @return $this
     */
    public function setDetail(string $detail): self
    {
        return $this->update(['message' => $detail]);
    }

    /**
     * @param array $updatedArray
     * @return $this
     */
    protected function update(array $updatedArray): self
    {
        $notifications = session('ygg_notifications');
        $notifications[$this->id] = array_merge($notifications[$this->id], $updatedArray);

        session()->put('ygg_notifications', $notifications);

        return $this;
    }

    /**
     * @return $this
     */
    public function setLevelSuccess(): self
    {
        return $this->update(['level' => 'success']);
    }

    /**
     * @return $this
     */
    public function setLevelInfo(): self
    {
        return $this->update(['level' => 'info']);
    }

    /**
     * @return $this
     */
    public function setLevelWarning(): self
    {
        return $this->update(['level' => 'warning']);
    }

    /**
     * @return $this
     */
    public function setLevelDanger(): self
    {
        return $this->update(['level' => 'danger']);
    }

    /**
     * @param bool $autoHide
     * @return $this
     */
    public function setAutoHide(bool $autoHide = true): self
    {
        return $this->update(['autoHide' => $autoHide]);
    }
}
