<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'type'              => $this->type,
            'short_description' => $this->short_description,
            'content'           => $this->content,
            'organization'      => $this->organization,

            // Vacancy & Salary
            'total_posts'       => $this->total_posts,
            'salary'            => $this->salary,

            // Dates
            'notification_date' => $this->notification_date?->format('Y-m-d'),
            'start_date'        => $this->start_date?->format('Y-m-d'),
            'end_date'          => $this->end_date?->format('Y-m-d'),
            'last_date'         => $this->last_date?->format('Y-m-d'),

            // Links
            'online_form'       => $this->online_form,
            'final_result'      => $this->final_result,
            'important_links'   => $this->important_links,

            // Classification
            'tags'              => $this->tags ?? [],
            'education'         => $this->education ?? [],

            // Status
            'is_featured'       => $this->is_featured,
            'is_published'      => $this->is_published,
            'is_upcoming'       => $this->is_upcoming,
            'view_count'        => $this->view_count,

            // SEO
            'meta_title'        => $this->meta_title,
            'meta_description'  => $this->meta_description,
            'meta_keywords'     => $this->meta_keywords,

            // Relations
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id'    => $this->category->id,
                    'name'  => $this->category->name,
                    'slug'  => $this->category->slug,
                    'icon'  => $this->category->icon,
                    'color' => $this->category->color,
                ];
            }),
            'state' => $this->whenLoaded('state', function () {
                return $this->state ? [
                    'id'   => $this->state->id,
                    'name' => $this->state->name,
                    'slug' => $this->state->slug,
                ] : null;
            }),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
