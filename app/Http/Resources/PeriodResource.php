<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ];
    }

    public function listPeriods()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
