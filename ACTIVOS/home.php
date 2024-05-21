<?php
session_start();
include("connection/ServerConfiguration.php");
include("connection/Dbconnection.php");
ob_start();

$iv_base64 = $_GET['c'];
$cifrado_base64 = $_GET['d'];
$iv = base64_decode($iv_base64);
$cifrado = base64_decode($cifrado_base64);
$dato_descifrado = openssl_decrypt($cifrado, $metodo, $clave, 0, $iv);

$_SESSION['area'] = $dato_descifrado;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/homestructure.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
  <link rel="icon" href="image/F.png" sizes="192x192" />
  <title>ASSETS-Files TI</title>
</head>

<body>
  <nav class="sb-topnav navbar navbar-expand navbar-dark">
    <a class="navbar-brand" href="index.html">
      <h1 class="logotipo">FISHER & PAYKEL</h1>
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto ml-md-0" style="position: absolute; right: 0.5cm">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#"><b>{{current_user.fullname}}</b></a><a class="dropdown-item" href="#"><b>{{current_user.user_type}}</b></a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ url_for('inicio_dica') }}">Volver al inicio</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ url_for('logout') }}">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <a class="nav-link" href="index.html">
              <div class="sb-nav-link-icon">
                <i class="fa-solid fa-house">
                  <spam>Inicio</spam>
                </i>
              </div>
            </a>
            <a class="nav-link" href="home.html">
              <div class="sb-nav-link-icon">
                <i class="fa-solid fa-file-excel">
                  <spam>Archivos excel</spam>
                </i>
              </div>
            </a>
          </div>
        </div>
      </nav>
    </div>
    <div id="layoutSidenav_content">
      <div class="filter-sh">
        <div class="icons" id="iconos">
          <i class="fa-solid fa-plus" id="open-popup"></i><spam class="vers"><i class="fa-solid fa-arrow-left"></i> Add File</spam>
        </div>
        <div>
          <div class="search-container">
            <input type="text" id="searchInput" placeholder="Buscar" oninput="searchMain()" />
            <button type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </div>
      <main class="mainContent" id="areas-container" style="display: flex; flex-wrap: wrap">
        <?php
        $are = $_SESSION['area']; 
        $stmt = $pdo->prepare("SELECT * FROM excel WHERE area = '$are'");
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        foreach($row as $cards){
          $ivs = openssl_random_pseudo_bytes(openssl_cipher_iv_length($metodo));

          $cifrado = openssl_encrypt($cards['idExcel'], $metodo, $clave, 0, $ivs);
          
          $iv_base647 = base64_encode($ivs);
          $cifrado_base647 = base64_encode($cifrado);
        ?>
        <div class="cards">
          <div class="face front">
            <img src="image/excel.png" alt="" style="object-fit: contain !important" />
            <div class="info">
              <h2 class="h2" ><?php echo $cards['nombre']; ?></h2>
              <p><?php echo $cards['fecha']; ?></p>
            </div>
          </div>
          <div class="face back">
            <div style="text-align: center">
              <h2 style="color: #000000; text-align:center !important;">Actions</h2>
              <p class="" style="color: #000000"><?php echo $cards['nombre'];?></p>
            </div>
            <div class="entornos" id="targetOne">
              
            </div>
            <div class="desarrollo" id="targetThree">
              <a href="show.php?gh=<?php echo $cifrado_base647; ?>&ty=<?php echo $iv_base647; ?>">
                <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
                <input type="hidden" id="fileName" name="txtarchivo" value="{{ planes[1] }}" />
                <button type="submit">
                  <i class="fa-solid fa-eye"></i>
                </button>
              </a>
              <a action="/editpl" method="post">
                <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
                <input type="hidden" id="fileName" name="txtfile" value="{{ planes[1] }}" />
                <button type="submit">
                <i class="fa-solid fa-pen-to-square"></i>
                </button>
              </a>
              <a action="/editpl" method="post">
                <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
                <input type="hidden" id="fileName" name="txtfile" value="{{ planes[1] }}" />
                <button type="submit">
                  <i class="fa-solid fa-file-excel"></i>
                </button>
              </a>
              <a action="/download_file" method="post">
                <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" />
                <input type="hidden" id="fileName" name="txtarchivo" value="{{ planes[1] }}" />
                <button type="submit">
                  <i class="fa-solid fa-file-pdf"></i>
                </button>
              </a>
            </div>
          </div>
        </div>
        <?php }?>
      </main>
      <div class="popup-container" id="popup">
        <div class="popup-content">
          <h2>Elegir opción de carga</h2>
          <p>Seleccione cómo desea cargar la imagen:</p>
          <div class="popup-buttons">
            <div class="prp">
              <i class="fa-regular fa-pen-to-square"></i>
              <button id="upload-manual">Subir Manualmente</button>
            </div>
            <div class="prp">
              <i class="fa-solid fa-file-excel"></i>
              <button id="upload-file">Subir Archivo</button>
            </div>
          </div>
        </div>
      </div>

      <div class="popup-container" id="upload-popup" style="display: none">
        <div class="popup-content">
          <h2>Subir Archivo(s)</h2>
          <div class="files">
            <label for="files">Archivo Excel:</label>
            <input type="file" id="file-input" accept=".xlsx" />
            <label for="files">Archivo de imagenes:</label>
            <input type="file" id="fileInput" accept="image/*" multiple>
          </div>
          <div class="popup-buttons prp" style="display: block">
            <button id="upload-cancel">Cancelar</button>
            <button id="upload-confirm">Previsualizar</button>
          </div>
        </div>
      </div>

      <div class="popup-container" id="up-popup" style="display: none">
        <div class="popup-content ete">
          <h2>Previsualizacion del archivo</h2>
          <p>Verifique que los datos y el archvo son correctos:</p>
          <div id="output"></div>
          <div id="imageNames"></div>
          <div class="popup-buttons prp" style="display: block">
            <button id="up-cancel">Cancelar</button>
            <button id="up-confirm">Confirmar</button>
          </div>
        </div>
      </div>

      <div id="loadingPopup" style="display: none">
        <div class="popup-content">
          <div class="spinner"></div>
          <p>Cargando...</p>
        </div>
      </div>

      <footer class="Myfooter">
        <p>© <span id="year"></span> Fisher & Paykel.</p>
      </footer>
    </div>
  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/ffc59e7747.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/database.js"></script>
  <script src="js/script.js"></script>
  <script src="js/wrap.js"></script>
  <script src="js/fun-up.js"></script>
</body>

</html>