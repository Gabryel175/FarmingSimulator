<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'link.php'; ?>
    <?php include 'functii.php';?>
    <title>
        <?php
        $uri = $_SERVER['REQUEST_URI'];
        $host = $_SERVER['HTTP_HOST'];
        $url = "http://$host$uri";
        $url=basename($url);
        if($url=='index.php'){echo 'Roleplay Farming Simulator 2022';}
        if($url=='rar.php'){echo 'Registrul Auto Roman Farming Simulator 2022';}
        if(strpos($url,"shop_individual.php")!==FALSE){
            echo $_GET['choice'].' items';
        }
        if($url=='primarie.php'){echo 'Primaria FS2022';}
        ?>
    </title>
</head>
<body>
    <header>
        <nav class="navbar navbar-light navbar-header">
            <div class="container-fluid">
                <a class="navbar-brand">
                    <div class="dropdown">
                        <?php
                            session_start();
                            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
                                ?>
                                    <button onclick="myFunction()" class="btn btn-outline-success login-button">
                                        <i class="fa fa-user"></i><?php echo $_SESSION['username']; ?><i class="fa fa-angle-down"></i>
                                    </button>
                                    <div id="myDropdown" class="dropdown-content">
                                        <?php echo '<br><a href="logout.php">Deconectare  <i class="fa fa-x"></i></a>'; ?>
                                        <a href="#about">Vezi detalii <i class="fa fa-circle-info"></i></a>
                                    </div>
                                <?php
                            }else{
                                ?>
                                    <a href="login.php"><button class="cont"><i class="fa fa-sign-in"></i></button></a>
                                    <a href="register.php"><button class="cont"><i class="fa fa-user-plus"></i></button></a>
                                <?php
                            }
                        ?>
                    </div>
                </a>
                <form class="d-flex w-50 mx-auto form-header"> 
                <input class="form-control form-control-sm me-1 search-header" type="search" placeholder="Caută aici ceea ce dorești să afli !" aria-label="Search">
                <button class="btn btn-outline-success search-header-button" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </nav>
    </header>
    
<script>
document.addEventListener("DOMContentLoaded", function () {
const scrollImages = document.querySelector(".scroll-images");
const scrollLength = scrollImages.scrollWidth - scrollImages.clientWidth;
const leftButton = document.querySelector(".left");
const rightButton = document.querySelector(".right");

function checkScroll() {
    const currentScroll = scrollImages.scrollLeft;
    if (currentScroll === 0) {
    leftButton.setAttribute("disabled", "true");
    rightButton.removeAttribute("disabled");
    } else if (currentScroll === scrollLength) {
    rightButton.setAttribute("disabled", "true");
    leftButton.removeAttribute("disabled");
    } else {
    leftButton.removeAttribute("disabled");
    rightButton.removeAttribute("disabled");
    }
}

scrollImages.addEventListener("scroll", checkScroll);
window.addEventListener("resize", checkScroll);
checkScroll();

function leftScroll() {
    scrollImages.scrollBy({
    left: -200,
    behavior: "smooth"
    });
}

function rightScroll() {
    scrollImages.scrollBy({
    left: 200,
    behavior: "smooth"
    });
}

leftButton.addEventListener("click", leftScroll);
rightButton.addEventListener("click", rightScroll);
});
</script>
<script>
document.addEventListener("DOMContentLoaded",function(){
const scrollImages = document.querySelector(".scroll-images2");
const scrollLength = scrollImages.scrollWidth - scrollImages.clientWidth;
const leftButton = document.querySelector(".left2");
const rightButton = document.querySelector(".right2");
function checkScroll() {
    const currentScroll = scrollImages.scrollLeft;
    if (currentScroll === 0) {
    leftButton.setAttribute("disabled", "true");
    rightButton.removeAttribute("disabled");
    } else if (currentScroll === scrollLength) {
    rightButton.setAttribute("disabled", "true");
    leftButton.removeAttribute("disabled");
    } else {
    leftButton.removeAttribute("disabled");
    rightButton.removeAttribute("disabled");
    }
}
scrollImages.addEventListener("scroll", checkScroll);
window.addEventListener("resize", checkScroll);
checkScroll();
function leftScroll2() {
    scrollImages.scrollBy({
    left: -200,
    behavior: "smooth"
    });
}

function rightScroll2() {
    scrollImages.scrollBy({
    left: 200,
    behavior: "smooth"
    });
}
leftButton.addEventListener("click", leftScroll2);
rightButton.addEventListener("click", rightScroll2);
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var col2Content = $("#col2-content");
        var originalCol2Content = col2Content.html(); // Salvăm conținutul original al col-2

        $(".link-hover").hover(
            function() {
                var targetId = $(this).data("target");
                col2Content.addClass("hovered").html($("#submenu-" + targetId).html());
            },
            function() {
                if (!$(".col-1:hover").length) {
                    col2Content.removeClass("hovered").html(originalCol2Content);
                }
            }
        );
    });
</script>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.login-button')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>