<?php

namespace App\Models;

use App\Core\DB;

class Article extends DB
{
    private $id;
    private $title;
    private $content;
    private $keywords;
    private $pictureUrl;
    private $categoryId;
    private $createdAt;
    private $creatorId;
    private $updatedAt;
    private $updatorId;
    private $publishedAt;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function getPictureUrl() {
        return $this->pictureUrl;
    }

    public function setPictureUrl($pictureUrl) {
        $this->pictureUrl = $pictureUrl;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function setCreatorId($creatorId) {
        $this->creatorId = $creatorId;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatorId() {
        return $this->updatorId;
    }

    public function setUpdatorId($updatorId) {
        $this->updatorId = $updatorId;
    }

    public function getPublishedAt() {
        return $this->publishedAt;
    }

    public function setPublishedAt($publishedAt) {
        $this->publishedAt = $publishedAt;
    }
}