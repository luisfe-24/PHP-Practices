<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag View</title>
    <link rel="stylesheet" href="./views/styles.css">
    <div class="page-header">
        <h1>CREATE TAG</h1>
    </div>
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="index.php?view=article">Article</a>
        <a href="index.php?view=tags">Tags</a>
    </div>

    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>

    <div id="tagForm">
        <form action="index.php?view=tags" name="bdblog" method="post">
            <input type="text" name="tagName" placeholder="Tag">
            <input id="submitTag" type="submit">
        </form>
    </div>

    <div class="tag-container">
        <?php foreach ($tagList as $tag): ?>
            <div class="tag-box">
                <p>ID: <?php echo htmlspecialchars($tag['id_tag']); ?></p>
                <p>Tag: <?php echo htmlspecialchars($tag['tagname']); ?></p>
                <div class="tag-actions">
                    <form action="index.php?view=tags" method="POST">
                        <input type="hidden" name="_method" value="DELETE" />
                        <input type="hidden" name="id_tag" value="<?php echo $tag['id_tag']; ?>">
                        <input id="deleteTag" type="submit" value="Delete" />
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="snackbar"><?php echo $tagResponse['message']; ?></div>


    <?php
    $json = json_encode($tagList);
    echo "<script>var tagList = $json;</script>";
    ?>

    <script>
        (function myFunction() {
            let x = document.getElementById("snackbar");
            let toastType = '<?php echo $tagResponse['status']; ?>'
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