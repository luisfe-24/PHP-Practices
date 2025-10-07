function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

// Get the modal
var modal = document.getElementById("myModal");
// Get the button that opens the modal
var btn = document.getElementById("myBtn");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
function showModalArticle(id_article, author, title, content, id_tag) {
  document.getElementById('id_article').value = id_article;
  document.getElementById('author').value = author;
  document.getElementById('title').value = title;
  document.getElementById('content').value = content;

  // Establecer el tag correspondiente
  let selectTag = document.getElementById('id_tag');
  selectTag.value = id_tag; // Asegúrate de que este valor se establezca correctamente

  // Mostrar el modal
  document.getElementById("myModal").style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
  modal.style.display = "none";

  let articleIdInput = document.getElementById("id_article");
  articleIdInput.value = ""

  let authorInput = document.getElementById("author");
  authorInput.value = ""
  
  let titleInput = document.getElementById("title");
  titleInput.value = ""

  let contentInput = document.getElementById("content");
  contentInput.value = ""
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}