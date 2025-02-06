<?php

namespace App\Dto;


class UveDataDto
{
    public int $uveId;
    public string $number;
    public string $title;
    public ?string $actNo;
    public ?\DateTimeImmutable $actDate;
    public string $status;
    public ?int $interval = null {
        set {
            $this->interval = $value;
        }
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(array $data)
    {
        $this->uveId = (int) ($data['uve_id'] ?? 0);
        $this->number = $data['number'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->actNo = $data['act_no'] ?? null;
        $this->actDate = isset($data['act_date']) ? new \DateTimeImmutable(date('Y-m-d H:i:s', strtotime($data['act_date']))) : null;
        $this->status = $data['status'] ?? '';
        $this->interval = isset($data['interval']) ? (int) $data['interval'] : null;
    }

    public function toArray(): array
    {
        return [
            'uve_id' => $this->uveId,
            'number' => $this->number,
            'title' => $this->title,
            'act_no' => $this->actNo,
            'act_date' => $this->actDate,
            'status' => $this->status,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'interval'   => $this->interval
        ];
    }
}

