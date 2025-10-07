<?php
class Tag
{
    private int $tagId;
    private String $tagName;
    private mysqli $conn;

    function __construct(String $tagName = "")
    {
        $this->tagName = $tagName;
    }

    public function setConn(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getTagName(): String
    {
        return $this->tagName;
    }
    public function setTagName(String $tagName)
    {
        $this->tagName = $tagName;
    }

    public function createTag()
    {
        $query = "INSERT INTO tags (Tagname) VALUES(?)";
        if ($stmt = $this->conn->prepare($query)) {
            $tagName = $this->getTagName();

            $stmt->bind_param("s", $tagName);
            if ($stmt->execute()) {
                echo "Artículo insertado correctamente";
            } else {
                echo "Error al insertar: " . $stmt->error;
            }
        }
    }

    public function getTagList()
    {
        $query = 'SELECT id_tag, tagname FROM Tags ORDER BY id_tag DESC';
        if ($stmt = $this->conn->prepare($query)) {

            $result = $this->conn->query($query);
            $tagList = [];
            if ($result) {

                while ($row = $result->fetch_assoc()) {
                    $tagList[] = $row;
                }
            } else {
                echo "Error en la consulta: " . $this->conn->error;
            }

            //echo json_encode($articleList);

            return $tagList;
        }
    }

    public function deleteArticle(int $tagId)
    {
        $query = "DELETE FROM Tags WHERE id_tag = ?";

        if ($stmt = $this->conn->prepare($query));

        $stmt->bind_param("i", $tagId);
        if ($stmt->execute()) {
            echo "Artículo eliminado correctamente";
        } else {
            echo "Error al eliminar: " . $stmt->error;
        }
    }

}
