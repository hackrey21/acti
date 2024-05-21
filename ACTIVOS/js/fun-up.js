var popup = document.getElementById("popup");
var uploadPopup = document.getElementById("upload-popup");
var upPopup = document.getElementById("up-popup");
var openPopupButton = document.getElementById("open-popup");
var uploadManualButton = document.getElementById("upload-manual");
var uploadFileButton = document.getElementById("upload-file");
var fileInput = document.getElementById("file-input");
var uploadCancelButton = document.getElementById("upload-cancel");
var uploadConfirmButton = document.getElementById("upload-confirm");
var upCancelButton = document.getElementById("up-cancel");
var upConfirmButton = document.getElementById("up-confirm");

function openPopup() {
  popup.style.display = "flex";
}

function closePopup() {
  popup.style.display = "none";
  uploadPopup.style.display = "none";
}

openPopupButton.addEventListener("click", openPopup);

popup.addEventListener("click", function (event) {
  if (event.target === popup) {
    closePopup();
  }
});

uploadManualButton.addEventListener("click", function () {
  window.location.href = "add_manu.html";
  closePopup();
});

uploadFileButton.addEventListener("click", function () {
  popup.style.display = "none";
  uploadPopup.style.display = "flex";
});

uploadCancelButton.addEventListener("click", function () {
  uploadPopup.style.display = "none";
  popup.style.display = "flex";
});

upCancelButton.addEventListener("click", function () {
  upPopup.style.display = "none";
  uploadPopup.style.display = "flex";
});

function showLoadingPopup() {
  document.getElementById("loadingPopup").style.display = "flex";
}

function hideLoadingPopup() {
  document.getElementById("loadingPopup").style.display = "none";
  upPopup.style.display = "flex";
}

uploadConfirmButton.addEventListener("click", function () {
  uploadPopup.style.display = "none";
  showLoadingPopup();
});

upConfirmButton.addEventListener("click", function () {
  upPopup.style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
  var imageNumbers = {};
  var imageFiles = {};

  document
    .getElementById("fileInput")
    .addEventListener("change", handleFileSelect, false);

  function handleFileSelect(event) {
    const files = event.target.files;
    const imageNamesDiv = document.getElementById("imageNames");
    imageNamesDiv.innerHTML = "";

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();

      reader.onload = function (event) {
        const image = new Image();
        image.src = event.target.result;
        image.style.maxWidth = "200px";
        image.style.marginRight = "10px";

        const fileName = file.name;
        const numbers = fileName.match(/\d+/g);
        if (numbers) {
          const numberString = numbers[0];
          imageNumbers[file.name] = numberString;
          file.value = numberString;
          file.img = String(image.src);
          file.nom = fileName;
          imageFiles[file.name] = file;
        }
      };

      reader.readAsDataURL(file);
    }
  }

  uploadConfirmButton.addEventListener("click", function () {
    var file = fileInput.files[0];
    var cont = 0;
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, { type: "array" });
      
        var sheetName = workbook.SheetNames[0];
        var sheet = workbook.Sheets[sheetName];

        var jsonData = XLSX.utils.sheet_to_json(sheet, {
          header: 1,
          raw: false,
          dateNF: 'yyyy-mm-dd'
        });
      
        jsonData = jsonData.map(row => row.map(cell => {
          if (typeof cell === 'number' && cell > 10000) {
            return new Date((cell - (25567 + 2)) * 86400 * 1000); 
          } else if (cell === 'empty' || cell == null) {
            return '';
          }
          return cell;
        }));
      
        var dataWithFileName = {
          fileName: file.name,
          data: jsonData,
        };
        console.log(jsonData);
        console.log(dataWithFileName);
      
        var searchTerm = "Numero de Activo".toLowerCase();
        var headerRow = jsonData.find((row) =>
          row.map((cell) => (typeof cell === 'string' ? cell.toLowerCase() : "")).includes(searchTerm)
        );

        if (headerRow) {
          var htmlTable = "<table>";

          htmlTable += "<thead><tr >";
          headerRow.forEach((cellValue) => {
            htmlTable += "<th class='col'>" + cellValue + "</th>";
            cont += 1;
          });
          htmlTable += "</tr></thead>";

          var fotoRow = -1;
          var fotoColumn = -1;

          for (var i = 0; i < jsonData.length; i++) {
            var rowIndex = i;

            for (var j = 0; j < jsonData[i].length; j++) {
              var cellValue = jsonData[i][j] != null ? jsonData[i][j] : "";

              if (cellValue.toLowerCase() === "foto") {
                fotoRow = rowIndex;
                fotoColumn = j;
                break;
              }
            }

            if (fotoRow !== -1) {
              break;
            }
          }
          console.log(imageFiles);
          let fileList = Object.entries(imageFiles).map(([key, value]) => [
            value.name,
            value.value,
            value.img,
          ]);

          console.log(fileList[0]);
          for (var i = 3; i < jsonData.length; i++) {
            var rowColor = "";

            if (jsonData[i][10] && jsonData[i][10].toLowerCase() === "no") {
              rowColor = "background-color: red;";
            }

            htmlTable += '<tr style="' + rowColor + '">';
            var conset = "";

            for (var z = 0; z < fileList.length; z++) {
              if (String(fileList[z][1]) === String(jsonData[i][1])) {
                console.log("hola");
                conset = `<img class="hola" src="${fileList[z][2]}" style="max-width: 200px; margin-right: 10px;">`;
              }
            }

            for (var j = 0; j < cont; j++) {
              var cellValue = jsonData[i][j] != null ? jsonData[i][j] : "";
              var cellContent =
                i > fotoRow && j === fotoColumn ? conset : cellValue;
              htmlTable += "<td>" + cellContent + "</td>";
            }

            htmlTable += "</tr>";
          }

          htmlTable += "</table>";
          htms =
            '<div class="comp"><h5>' +
            jsonData[0] +
            "</h5><h5>" +
            jsonData[1] +
            "</h5></div>";
          var outputElement = document.getElementById("output");
          outputElement.innerHTML = htms + htmlTable;

          upConfirmButton.addEventListener("click", function () {
            sendDataToPHP(dataWithFileName);
          });
        } else {
          alert(
            'No se encontró el encabezado "Nombre de Activo" en los datos.'
          );
        }
        hideLoadingPopup();
      };
      reader.readAsArrayBuffer(file);
    } else {
      alert("Por favor, seleccione un archivo.");
      hideLoadingPopup();
    }

    function sendDataToPHP(data) {
      console.log(data['data']);
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "save_data.php", true);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onload = function () {
        if (xhr.status === 200) {
          alert("Los datos se han guardado exitosamente.");
        } else {
          alert("Hubo un error al guardar los datos.");
        }
      };
      xhr.send(JSON.stringify(data));
    }
  });
});

function getImageHTML(file) {
  if (!file) return "";

  return `<img class="hola" src="${URL.createObjectURL(
    file
  )}" style="max-width: 200px; margin-right: 10px;">`;
}
