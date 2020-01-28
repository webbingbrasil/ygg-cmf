<?php


namespace Ygg\Old\Actions;


use Throwable;

trait WithBasicResponseActions
{
    /**
     * @param string $link
     * @return array
     */
    protected function link(string $link): array
    {
        return [
            'action' => 'link',
            'link' => $link
        ];
    }

    /**
     * @return array
     */
    protected function reload(): array
    {
        return [
            'action' => 'reload'
        ];
    }

    /**
     * @param string $message
     * @return array|void
     */
    protected function info(string $message): ?array
    {
        return [
            'action' => 'info',
            'message' => $message
        ];
    }

    /**
     * @param string $bladeView
     * @param array  $params
     * @return array|void
     * @throws Throwable
     */
    protected function view(string $bladeView, array $params = []): ?array
    {
        return [
            'action' => 'view',
            'html' => view($bladeView, $params)->render()
        ];
    }
}