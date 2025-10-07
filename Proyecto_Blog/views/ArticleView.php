<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article View</title>
    <link rel="stylesheet" href="./views/styles.css?v=1.0">
    <div class="page-header">
        <h1>CREATE ARTICLE</h1>
    </div>

</head>

<body>

    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php?view=article">Articles</a>
        <a href="index.php?view=tags">Tags</a>
    </div>

    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="container">
        <form action="index.php?view=article" name="bdblog" method="post">
            <input type="text" name="authorName" placeholder="Author">
            <input type="text" name="title" placeholder="Title">
            <textarea type="text" name="articleContent" placeholder="Content" rows="4" cols="50"></textarea>
            <select name="idTag" id="idTag">
                <option value="">-- Select Tag --</option>
                <?php foreach ($tagList as $tag): ?>
                    <option value="<?php echo $tag['id_tag'] ?>"><?php echo $tag['tagname'] ?></option>
                <?php endforeach; ?>
            </select>
            <div><input id="submitArticle" type="submit"></div>
        </form>
    </div>

    <div class="filter-container">
        <form action="index.php?view=article" method="get">
            <input type="hidden" name="view" value="article" />

            <label for="start_date">Fecha de Inicio:</label>
            <input type="date" name="start_date" id="start_date" class="input-field">

            <label for="end_date">Fecha de Fin:</label>
            <input type="date" name="end_date" id="end_date" class="input-field">

            <label for="tagname">Tag:</label>
            <select name="tagname" id="tagname" class="input-field">
                <option value="">-- Select Tag --</option>
                <?php foreach ($tagList as $tag): ?>
                    <option value="<?php echo $tag['tagname']; ?>"><?php echo $tag['tagname']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Filtrar" class="submit-btn">
        </form>
    </div>
    <div id="snackbar"><?php echo $articleResponse['message']; ?></div>

    <table id="articleTable" border="1">
        <thead>
            <tr>
                <th>ID Article</th>
                <th>Author</th>
                <th>Title</th>
                <th>Content</th>
                <th>Tag</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($articleList)): ?>
                <?php foreach ($articleList as $article): ?>
                    <tr>
                        <td><?php echo $article['id_article']; ?></td>
                        <td><?php echo $article['author']; ?></td>
                        <td><?php echo $article['title']; ?></td>
                        <td><?php echo $article['content']; ?></td>
                        <td><?php echo $article['tagname']; ?></td>
                        <td><?php echo $article['created_at']; ?></td>

                        <td>
                            <button id="myBtn_<?php echo $article['id_article']; ?>"
                                onclick="showModalArticle(
                                    '<?php echo $article['id_article']; ?>', 
                                    '<?php echo htmlspecialchars($article['author']); ?>', 
                                    '<?php echo htmlspecialchars($article['title']); ?>', 
                                    '<?php echo htmlspecialchars($article['content']); ?>', 
                                    '<?php echo $article['id_tag']; ?>'
                                )">Edit</button>

                            <form action="index.php?view=article" method="POST">
                                <input type="hidden" name="_method" value="DELETE" />
                                <input type="hidden" name="id_article" value="<?php echo $article['id_article']; ?>">
                                <input id="deleteArticle" type="submit" value="Delete" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No articles found with the applied filters.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="flex-container" id="container">
            <!-- Modal content -->
            <div class="modal-content custom-size">
                <form action="index.php?view=article" method="POST" class="flex-container">
                    <span class="close">&times;</span>
                    <input type="hidden" name="_method" value="PUT" />
                    <input id="id_article" type="hidden" name="id_article" value=""/>
                    <input id="author" type="text" name="authorName" value="" />
                    <input id="title" type="text" name="title" value="" />
                    <textarea id="content" type="text" name="articleContent" rows="2" cols="50"></textarea>
                    <select name="id_tag" id="id_tag">
                        <option value="">-- Select Tag --</option>
                        <?php foreach ($tagList as $tag): ?>
                            <option value="<?php echo $tag['id_tag']; ?>" <?php echo ($tag['id_tag'] == $article['id_tag']) ? 'selected' : ''; ?>>
                                <?php echo $tag['tagname']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input id="submit" type="submit" value="Update" />
                </form>
            </div>
        </div>
    </div>

    <?php
    $json = json_encode($articleList);
    echo "<script>var articleList = $json;</script>";
    ?>

    <?php
    $json = json_encode($tagList);
    echo "<script>var tagList = $json;</script>";
    ?>

    <script>
        (function myFunction() {
            let x = document.getElementById("snackbar");
            let toastType = '<?php echo $articleResponse['status']; ?>'
            if (toastType == 'success') {
                x.className = "show green-background";

            } else {
                x.className = "show red-background";
            }
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 3000);
        })()
    </script>

    <script src="./views/index.js"></script>

</body>

</html>