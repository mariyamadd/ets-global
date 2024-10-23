<?php

namespace App\Controller;

use App\Document\Article;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;


class ArticleController extends AbstractController
{

    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    #[Route('/api/article', name: 'article_list', methods: ['GET'])]
    public function listAll(): JsonResponse
    {
        $articles = $this->documentManager->getRepository(Article::class)->findAll();

        $data = [];

        foreach ($articles as $article) {
            $data[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'author' => $article->getAuthor(),
                'publishedDate' => $article->getPublishedDate()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
    #[Route('/api/article', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article();
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $article->setAuthor($data['author']);
        $article->setPublishedDate(new \DateTime());

        $this->documentManager->persist($article);
        $this->documentManager->flush();

        return new JsonResponse(['status' => 'Article created'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/article/{id}', name: 'article_show', methods: ['GET'])]
    public function getArticle(string $id): JsonResponse
    {
        $article = $this->documentManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['status' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'author' => $article->getAuthor(),
            'publishedDate' => $article->getPublishedDate()->format('Y-m-d H:i:s'),
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/api/article/{id}', methods: ['PUT'])]
    public function updateArticle(string $id, Request $request): JsonResponse
    {
        $article = $this->documentManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['status' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $article->setAuthor($data['author']);
        $article->setPublishedDate(new DateTime($data['publishedDate']));

        $this->documentManager->flush();

        return new JsonResponse(['status' => 'Article updated'], JsonResponse::HTTP_OK);
    }

    #[Route('/api/article/{id}', name: 'delete_article', methods: ['DELETE'])]
    public function deleteArticle(string $id): JsonResponse
    {
        $article = $this->documentManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return new JsonResponse(['status' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->documentManager->remove($article);
        $this->documentManager->flush();

        return new JsonResponse(['status' => 'Article deleted'], JsonResponse::HTTP_OK);
    }
}
