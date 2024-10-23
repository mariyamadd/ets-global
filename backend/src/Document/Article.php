<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use DateTime;

#[MongoDB\Document(collection: 'articles')]
class Article
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    private $title;

    #[MongoDB\Field(type: 'string')]
    private $content;

    #[MongoDB\Field(type: 'string')]
    private $author;

    #[MongoDB\Field(type: 'date')]
    private $publishedDate;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getPublishedDate(): ?DateTime
    {
        return $this->publishedDate;
    }

    public function setPublishedDate(DateTime $publishedDate): self
    {
        $this->publishedDate = $publishedDate;
        return $this;
    }
}
