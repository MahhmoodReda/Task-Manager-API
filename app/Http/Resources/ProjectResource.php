<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TaskCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'created_at' =>$this->created_at,
            'updated_at' =>$this->updated_at,
            'user_id' =>$this->user_id,
            'tasks'=> TaskResource::collection($this->whenLoaded('tasks')),
            'users'=> UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
