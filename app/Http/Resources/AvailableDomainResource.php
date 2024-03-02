<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailableDomainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'domain_name'        => $this->domain_name,
            'is_job'             => $this->is_job,
            'sub_niche_name'     =>  '-',
            'niche_name'         =>  '-',
        ];
    }
}
