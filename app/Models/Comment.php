<?php

namespace App\Models;

use App\Core\DB;

class Comment extends DB{
    private $id;
    private $articleId;
    private $commentResponseId;
    private $userId;
    private $content;
    private $createdAt;
    private $valid;
    private $validateAt;
    private $validatorId;
    private $updatedAt;

    public function __construct($id, $articleId, $commentResponseId, $userId, $content, $createdAt, $valid, $validateAt, $validatorId, $updatedAt) {
        $this->id = $id;
        $this->articleId = $articleId;
        $this->commentResponseId = $commentResponseId;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->valid = $valid;
        $this->validateAt = $validateAt;
        $this->validatorId = $validatorId;
        $this->updatedAt = $updatedAt;
    }

    public function getId() {
        return $this->id;
    }

    public function getArticleId() {
        return $this->articleId;
    }

    public function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    public function getCommentResponseId() {
        return $this->commentResponseId;
    }

    public function setCommentResponseId($commentResponseId) {
        $this->commentResponseId = $commentResponseId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function isValid() {
        return $this->valid;
    }

    public function setValid($valid) {
        $this->valid = $valid;
    }

    public function getValidateAt() {
        return $this->validateAt;
    }

    public function setValidateAt($validateAt) {
        $this->validateAt = $validateAt;
    }

    public function getValidatorId() {
        return $this->validatorId;
    }

    public function setValidatorId($validatorId) {
        $this->validatorId = $validatorId;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
}