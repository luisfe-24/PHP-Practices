<?php
class TagController
{
    private string $method;
    private Tag $tag;
    private $tagList;
    private $params;
    private $response = ['status' => '', 'message' => ''];
    

    public function __construct(string $method)
    {
        $this->method = $method;
        $this->tag = new tag();
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

    public function getTagList()
    {
        return $this->tagList;
    }

    public function main()
    {
        switch ($this->method) {
            case 'GET':
                $this->tagList = $this->tag->getTagList();
                if (array_key_exists("error", $this->tagList)) {
                    $this->setResponse('error', '400 Bad Request' . $this->tagList["error"]);
                } else {
                    $this->setResponse('success', '200 OK');
                }
                break;
            case 'POST':
                if (!empty($this->params['tagName'])) {
                    $this->tag->setTagName($this->params['tagName']);
                    $this->tag->createTag();
                    $this->setResponse('success', '201 Created.');
                } else {
                    $this->setResponse('error', '400 Bad Request');
                }
                $this->tagList = $this->tag->getTagList();
                break;
            case 'DELETE':
                if (!empty($this->params['id_tag'])) {
                    $id_tag = $this->params['id_tag'];
                    $this->tag->deleteArticle($id_tag);
                    $this->setResponse('success', '200 OK');
                } else {
                    $this->setResponse('error', '400 Bad Request');
                }                
                $this->tagList = $this->tag->getTagList();
                break;
            default:
                echo json_encode(array('message' => 'Invalid request method'));
                break;
        }
    }
}
