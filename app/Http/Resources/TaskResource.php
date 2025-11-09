<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * @var \App\Models\Task
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'priority' => $this->resource->priority,

            'done' => filled($this->resource->done_at),
            'done_at' => $this->resource->done_at,

            'due_date' => $this->resource->due_date,
            'deadline_missed' => $this->resource->deadlineMissed(),
            'pinned' => (bool) $this->pinned
        ];
    }
}
