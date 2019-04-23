<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 23 April 2019, 11:22 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Model\Popo;


class ResponsePopo
{
    private $code;
    private $status;
    private $data;
    private $notify;
    private $alert;

    /**
     * ResponsePopo constructor.
     * @param int $code
     * @param string $status
     * @param array $data
     * @param array $notify
     * @param array $alert
     */
    public function __construct($code = 200, $status = '', $data = [], $notify = [], $alert = [])
    {
        $this->code   = $code;
        $this->status = $status;
        $this->data   = $data;
        $this->notify = $notify;
        $this->alert  = $alert;
    }

    public function __sleep(): array
    {
        return [
            'code' => $this->code,
            'status' => $this->status,
            'data' => $this->data,
            'notify' => $this->notify,
            'alert' => $this->alert
        ];
    }

    public function serialize(): array
    {
        return $this->__sleep();
    }

    public function withNotifyLevel(string $level): ResponsePopo
    {
        $level = strtolower($level);
        foreach ($this->notify as $v)
        {
            $v['level'] = $level;
        }
    }

    public function withAlertLevel(string $level): ResponsePopo
    {
        $level = strtolower($level);
        foreach ($this->alert as $v)
        {
            $v['level'] = $level;
        }
    }

    public function withLevel(string $level): ResponsePopo
    {
        return $this
            ->withAlertLevel($level)
            ->withNotifyLevel($level);
    }

    public function withAlertDuration(int $duration): ResponsePopo
    {
        foreach ($this->alert as $v)
        {
            $v['duration'] = $duration;
        }
    }
}

?>
