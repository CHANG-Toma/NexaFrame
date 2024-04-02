<?php

namespace App\Models;

use App\Core\DB;

class Article extends DB
{
    private ?int $id = null;
    protected string $title;
    protected string $content;
    protected string $keywords;
    protected string $picture_url;
    protected int $id_category;
    protected string $created_at;
    protected int $id_creator;
    protected ?string $updated_at;
    protected ?int $id_updator;
    protected ?string $published_at;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    public function setPictureUrl($pictureUrl)
    {
        $this->picture_url = $pictureUrl;
    }

    public function getCategoryId()
    {
        return $this->id_category;
    }

    public function setCategoryId($categoryId)
    {
        $this->id_category = $categoryId;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    public function getCreatorId()
    {
        return $this->id_creator;
    }

    public function setCreatorId($creatorId)
    {
        $this->id_creator = $creatorId;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    public function getUpdatorId()
    {
        return $this->id_updator;
    }

    public function setUpdatorId($updatorId)
    {
        $this->id_updator = $updatorId;
    }

    public function getPublishedAt()
    {
        return $this->published_at;
    }

    public function setPublishedAt($publishedAt)
    {
        $this->published_at = $publishedAt;
    }
}