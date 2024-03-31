<?php

namespace App\Models;

use App\Core\DB;

class Comment extends DB{
    private ?int $id = null;
    protected int $id_article;
    protected ?int $id_comment_response;
    protected int $id_user;
    protected string $content;
    protected string $created_at;
    protected bool $valid;
    protected string $validate_at;
    protected ?int $id_validator;
    protected string $updated_at;

    public function getId() {
        return $this->id;
    }

    public function getArticleId() {
        return $this->id_article;
    }

    public function setArticleId($articleId) {
        $this->id_article = $articleId;
    }

    public function getCommentResponseId() {
        return $this->id_comment_response;
    }

    public function setCommentResponseId($commentResponseId) {
        $this->id_comment_response = $commentResponseId;
    }

    public function getUserId() {
        return $this->id_user;
    }

    public function setUserId($userId) {
        $this->id_user = $userId;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;
    }

    public function isValid() {
        return $this->valid;
    }

    public function setValid($valid) {
        $this->valid = $valid;
    }

    public function getValidateAt() {
        return $this->validate_at;
    }

    public function setValidateAt($validateAt) {
        $this->validate_at = $validateAt;
    }

    public function getValidatorId() {
        return $this->id_validator;
    }

    public function setValidatorId($validatorId) {
        $this->id_validator = $validatorId;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updated_at = $updatedAt;
    }
}