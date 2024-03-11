<?php
namespace App\Models;

use App\Core\DB;
use App\Models\user;

Class Page extends DB
{

    private ?int $id = null;
    protected string $url;
    protected string $title;
    protected string $html;
    protected string $css;
    protected string $meta_description;
    protected string $created_at;
    protected string $id_creator;
    protected user $creator;
    protected ?string $updated_at;
    protected ?string $id_updator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = strtolower($url);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function setHtml(string $html): void
    {
        $this->html = $html;
    }

    public function getCss(): string
    {
        return $this->html;
    }

    public function setCss(string $css): void
    {
        $this->css = $css;
    }

    public function getMetaDescription(): string
    {
        return $this->meta_description;
    }

    public function setMetaDescription(string $meta_description): void
    {
        $this->meta_description = $meta_description;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getIdCreator(): string
    {
        return $this->id_creator;
    }

    public function setIdCreator(string $id_creator): void
    {
        $this->id_creator = $id_creator;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getIdUpdator(): ?string
    {
        return $this->id_updator;
    }

    public function setIdUpdator(?string $id_updator): void
    {
        $this->id_updator = $id_updator;
    }

    public function getCreator(): user
    {
        return $this->creator;
    }

    public function setCreator(user $creator): void
    {
        $this->creator = $creator;
    }
}