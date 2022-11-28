<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToolResource extends JsonResource
{
    /**d
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
            'image' => $this->image,
            'status' => $this->status,
        ];
    }
}
