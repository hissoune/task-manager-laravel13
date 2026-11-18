<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description,
'status' => $this->getRawOriginal('status'),
                'completion_summary' => $this->completion_summary,
                'due_date' => $this->due_date,

                'employee' => [
                    'id' => $this->employee?->id,
                    'name' => $this->employee?->name,
                ],

                'supervisor' => [
                    'id' => $this->supervisor?->id,
                    'name' => $this->supervisor?->name,
                ],

                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                ]; 
   }
}
