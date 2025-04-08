<?php

namespace App\Livewire\Wireable;

use Livewire\Wireable;
use App\Models\Post;

class PostData implements Wireable
{
    public ?int $id = null;
    public string $title = '';
    public string $content = '';
    public bool $is_published = false;

    public static function fromModel(Post $post): self
    {
        $data = new self();
        $data->id = $post->id;
        $data->title = $post->title;
        $data->content = $post->content;
        $data->is_published = $post->is_published;

        return $data;
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ];
    }

    public static function fromLivewire($value)
    {
        $data = new self();
        $data->id = $value['id'] ?? null;
        $data->title = $value['title'] ?? '';
        $data->content = $value['content'] ?? '';
        $data->is_published = $value['is_published'] ?? false;

        return $data;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ];
    }
}
