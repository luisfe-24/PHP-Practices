<?php
class ArticleController
{

    private string $method;
    private Article $article;
    private Tag $tag;
    private $articleList;
    private $params;
    private $conn;
    private $response = ['status' => '', 'message' => ''];


    public function __construct(string $method)
    {
        $this->method = $method;
        $this->article = new Article();
        $this->article->setConn(Conectar::conexion());
        $this->tag = new Tag();
        $this->tag->setConn(Conectar::conexion());


        $this->params = $_POST;
    }

    public function getResponse()
    {
        return $this->response;
    }

    private function setResponse(string $status, string $message)
    {
        $this->response['status'] = $status;
        $this->response['message'] = $message;
    }

    public function getArticleList()
    {
        return $this->articleList;
    }
    public function getTag()
    {
        return $this->tag;
    }

    public function main()
    {
        switch ($this->method) {
            case 'GET':
                $startDate = !empty($_GET['start_date']) ? $_GET['start_date'] : null;
                $endDate = !empty($_GET['end_date']) ? $_GET['end_date'] : null;
                $tagname = !empty($_GET['tagname']) ? $_GET['tagname'] : null;

                $this->articleList = $this->article->getArticleList($startDate, $endDate, $tagname);
                if (array_key_exists("error", $this->articleList)) {
                    $this->setResponse('error', '400 Bad Request' . $this->articleList["error"]);
                } else {
                    $this->setResponse('success', '200 OK');
                }
                break;

            case 'POST':
                if (!empty($this->params['title']) && !empty($this->params['articleContent']) && !empty($this->params['authorName']) && !empty($this->params['idTag'])) {
                    $this->article->setTitle($this->params['title']);
                    $this->article->setArticleContent($this->params['articleContent']);
                    $this->article->setAuthorName($this->params['authorName']);
                    $this->article->setTagId($this->params['idTag']);
                    $this->article->createArticle();
                    $this->setResponse('success', '201 Articulo creado correctamente.');
                } else {
                    $this->setResponse('error', '400 Bad Request');
                }
                break;
            case 'PUT':
                if (!empty($this->params['id_article']) && !empty($this->params['title']) && !empty($this->params['articleContent']) && !empty($this->params['authorName']) && !empty($this->params['id_tag'])) {
                    $id_article = $this->params['id_article'];
                    $title = $this->params['title'];
                    $articleContent = $this->params['articleContent'];
                    $authorName = $this->params['authorName'];
                    $id_tag = $this->params['id_tag'];
                    $this->article->editArticle($id_article, $authorName, $title, $articleContent, $id_tag);
                    $this->setResponse('success', '200 OK');
                } else {
                    $this->setResponse('error', 'Bad Request');
                }
                break;
            case 'DELETE':
                if (!empty($this->params['id_article'])) {
                    $id_article = $this->params['id_article'];
                    $this->article->deleteArticle($id_article);
                    $this->setResponse('success', '200 OK');
                } else {
                    $this->setResponse('error', '400 Bad Request');
                }
                break;
            default:
            $this->setResponse('error', '405 Method Not Allowed');
                break;
        }

        if (!$this->articleList) {
            $this->articleList = $this->article->getArticleList();
        }
    }
}
