<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportPostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "post" => $this->whenLoaded('post', function () {
                return [
                    "id" => $this->post->id,
                    "title" => $this->post->title,
                    "image" => $this->post->image  ? asset("/storage/posts/{$this->post->image}"): null,
                    "news" => $this->post->news,
                ];
            }),
            "author" => $this->whenLoaded('writer', function () {
                return [
                    "id" => $this->writer->id,
                    "username" => $this->writer->username,
                ];
            }),
            "report_by" => $this->whenLoaded('reporter', function () {
                return [
                    "id" => $this->reporter->id,
                    "username" => $this->reporter->username,
                ];
            }),
            "reason"=>$this->reason,
        ];
    }

}
