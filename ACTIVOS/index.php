<?php
session_start();
include("connection/ServerConfiguration.php");
include("connection/Dbconnection.php");
ob_start();

$areaO = "1";
$areaT = "";
$areaR = "";
$areaF = "";
$areaI = "";
$areaS = "";


$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($metodo));

$cifrado = openssl_encrypt($areaO, $metodo, $clave, 0, $iv);

$iv_base64 = base64_encode($iv);
$cifrado_base64 = base64_encode($cifrado);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/structure.css">
    <link
    rel="icon"
    href="image/F.png"
    sizes="192x192"
  />
    <title>ASSETS</title>
</head>

<body>
    <header>
        <div class="linea-verde"></div>
        <div class="contenedor-imagen">
            <img src="image/fisher.jpg" alt="Imagen" />
        </div>
    </header>
    <main class="mycards">
        <div class="card">
            <a href="home.php?d=<?php echo $cifrado_base64?>&c=<?php echo $iv_base64?>" >

                <div class="face front">
                    <img src="image/ti.png" alt="" />
                    <div class="info">
                        <h2 style="font-size: 1.5rem;">TI</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
        <div class="card">
            <a href="">

                <div class="face front">
                    <img src="image/finanzas.png" alt="" />
                    <div class="info">
                        <h2>Finanzas</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
        <div class="card">
            <a href="">

                <div class="face front">
                    <img alt="" />
                    <div class="info">
                        <h2>Progress</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
        <div class="card">
            <a href="">

                <div class="face front">
                    <img  alt="" />
                    <div class="info">
                        <h2>Progress</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
        <div class="card">
            <a href="">

                <div class="face front">
                    <img  alt="" />
                    <div class="info">
                        <h2>Progress</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
        <div class="card">
            <a href="">

                <div class="face front">
                    <img  alt="" />
                    <div class="info">
                        <h2>Progress</h2>
                    </div>
                </div>
                <div class="face back">
                </div>

            </a>
        </div>
    </main>
    <footer>
        <p>© <span id="year"></span> Fisher & Paykel.</p>
    </footer>
    <script>
        var tsu = document.querySelector("#targetOne");
        var ing = document.querySelector("#targetThree");

        tsu.addEventListener("click", () => {
            window.location = "home_user";
        });
        ing.addEventListener("click", () => {
            window.location = "home_user";
        });
    </script>
    <!--PARTE DEL MAESTRO-->
    <script>
        var tsu = document.querySelector("#adminTagone");
        var ing = document.querySelector("#adminTagonethree");

        tsu.addEventListener("click", () => {
            window.location = "home_admin";
        });
        ing.addEventListener("click", () => {
            window.location = "home_admin";
        });
    </script>
    <script defer>
        function reemplazarImagenesFaltantes() {
            const imagenesSinSrc = document.querySelectorAll('img:not([src])');
            const imagenPredeterminada = 'image/progress.png';
            imagenesSinSrc.forEach(function (img) {
                img.src = imagenPredeterminada;
            });
        }
        document.addEventListener('DOMContentLoaded', reemplazarImagenesFaltantes);
    </script>
    <script src="js/database.js"></script>


</body>

</html>