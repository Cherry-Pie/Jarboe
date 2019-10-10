<?php

namespace Yaro\Jarboe\Http\Controllers\Traits;

trait NotifyTrait
{
    /**
     * Add notification.
     *
     * @param string $title
     * @param string|null $content
     * @param int $timeout
     * @param string|null $color
     * @param string|null $icon
     * @param string $type
     */
    protected function notify(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null, string $type = 'small')
    {
        $ident = 'jarboe_notifications.'. $type;

        $messages = session()->get($ident, []);
        $messages[] = [
            'title' => $title,
            'content' => $content,
            'color' => $color,
            'icon' => $icon,
            'timeout' => $timeout,
        ];

        session()->flash($ident, $messages);
    }

    protected function notifySmall(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null)
    {
        $this->notify($title, $content, $timeout, $color, $icon, 'small');
    }

    protected function notifyBig(string $title, string $content = null, int $timeout = 4000, string $color = null, string $icon = null)
    {
        $this->notify($title, $content, $timeout, $color, $icon, 'big');
    }

    protected function notifySmallSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#739E73', 'fa fa-check', 'small');
    }

    protected function notifySmallDanger(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C46A69', 'fa fa-warning shake animated', 'small');
    }

    protected function notifySmallWarning(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C79121', 'fa fa-shield fadeInLeft animated', 'small');
    }

    protected function notifySmallInfo(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#3276B1', 'fa fa-bell swing animated', 'small');
    }

    protected function notifyBigSuccess(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#739E73', 'fa fa-check', 'big');
    }

    protected function notifyBigDanger(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C46A69', 'fa fa-warning shake animated', 'big');
    }

    protected function notifyBigWarning(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#C79121', 'fa fa-shield fadeInLeft animated', 'big');
    }

    protected function notifyBigInfo(string $title, string $content = null, int $timeout = 4000)
    {
        $this->notify($title, $content, $timeout, '#3276B1', 'fa fa-bell swing animated', 'big');
    }
}
