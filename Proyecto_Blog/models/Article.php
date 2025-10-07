<?php
class Article
{
    private int $articleId;
    private int $tagId;
    private String $title;
    private String $authorName;
    private String $articleContent;
    private String $createdAt;
    private mysqli $conn;

    function __construct(String $authorName = "", String $title = "", String $articleContent = "")
    {
        $this->title = $title;
        $this->articleContent = $articleContent;
        $this->authorName = $authorName;
    }

    public function setConn(mysqli $conn)
    {
        $this->conn = $conn;
    }

    //Get y set de Titulo, Autor y Contenido

    public function setTagId(String $tagId)
    {
        $this->tagId = $tagId;
    }

    public function getAuthorName(): String
    {
        return $this->authorName;
    }
    public function setAuthorName(String $authorName)
    {
        $this->authorName = $authorName;
    }

    public function getTitle(): String
    {
        return $this->title;
    }
    public function setTitle(String $title)
    {
        $this->title = $title;
    }

    public function getArticleContent(): String
    {
        return $this->articleContent;
    }
    public function setArticleContent(String $articleContent)
    {
        $this->articleContent = $articleContent;
    }

    public function createArticle()
    {
        $query = "INSERT INTO Article (Author, Title, Content) VALUES(?, ?, ?)";
        if ($stmt = $this->conn->prepare($query)) {
            $authorName = $this->getAuthorName();
            $title = $this->getTitle();
            $content = $this->getArticleContent();
            $tagId = $this->tagId;

            $stmt->bind_param("sss", $authorName, $title, $content);
            if ($stmt->execute()) {
                echo "";
                $lastArticleId = $stmt->insert_id;
                $queryArticleTags = "INSERT INTO article_tag (id_article, id_tag) VALUES(?, ?)";
                if ($stmtArticleTags = $this->conn->prepare($queryArticleTags)) {
                    $stmtArticleTags->bind_param("ii", $lastArticleId, $tagId);
                    if ($stmtArticleTags->execute()) {
                        return true;
                    } else {
                        return ["error" => $stmtArticleTags->error];
                    }
                }
            } else {
                return ["error" => $stmt->error];
            }
        }
    }

    public function getArticleList($startDate = null, $endDate = null, $tagname = null)
    {
        // Base query
        $query = 'SELECT a.id_article, a.author, a.title, a.content, a.created_at, t.id_tag, t.tagname 
                  FROM Article a 
                  INNER JOIN article_tag art ON art.id_article = a.id_article 
                  LEFT JOIN tags t ON art.id_tag = t.id_tag
                  WHERE 1=1';

        // Filtros dinámicos
        if ($startDate) {
            $query .= ' AND a.created_at >= ?';
        }
        if ($endDate) {
            $query .= ' AND a.created_at <= ?';
        }
        if ($tagname) {
            $query .= ' AND t.tagname = ?';
        }

        $query .= ' ORDER BY a.created_at DESC';

        // Preparar y ejecutar la consulta
        
        
        if ($stmt = $this->conn->prepare($query)) {
            // Vincular parámetros
            $types = '';
            $params = [];

            if ($startDate) {
                $types .= 's';
                $params[] = $startDate;
            }
            if ($endDate) {
                $types .= 's';
                $params[] = $endDate;
            }
            if ($tagname) {
                $types .= 's';
                $params[] = $tagname;
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $articleList = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $articleList[] = $row;
                }
            }

            // Devolver el resultado
            return $articleList; // Este return debe estar aquí, dentro del if
        } 
    }

    public function editArticle(int $articleID, $author, $title, $content, $id_tag)
    {
        $query = 'UPDATE Article SET author = ?, title = ?, content = ? WHERE id_article = ?';
        $query2 = 'UPDATE article_tag SET id_tag = ? WHERE id_article = ?';

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("sssi", $author, $title, $content, $articleID);
            if ($stmt->execute()) {
                echo " Artículo editado correctamente";

                if ($stmt2 = $this->conn->prepare($query2)) {
                    $stmt2->bind_param("ii", $id_tag, $articleID);
                    if ($stmt2->execute()) {
                        echo "Tag del artículo editado correctamente";
                    } else {
                        echo "Error al editar el tag: " . $stmt2->error;
                    }
                } else {
                    echo "Error al preparar la consulta del tag: " . $this->conn->error;
                }
            } else {
                echo "Error al editar el artículo: " . $stmt->error;
            }
        }
    }
    public function deleteArticle(int $articleID)
    {
        $query = "DELETE FROM article_tag WHERE id_article = ?";
        $query2 = "DELETE FROM article WHERE id_article = ?";

        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("i", $articleID);
            if ($stmt->execute()) {
                echo "Artículo eliminado correctamente";

                if ($stmt2 = $this->conn->prepare($query2)) {
                    $stmt2->bind_param("i", $articleID);
                    if ($stmt->execute()) {
                        echo "Artículo eliminado correctamente";
                    } else {
                        echo "Error al eliminar: " . $stmt2->error;
                    }
                }
            } else {
                echo "Error al eliminar: " . $stmt->error;
            }
        }
    }
}
