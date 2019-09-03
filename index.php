<?php
session_start();

// Persistencia:
$nombre="";
$apellido="";
$telefono="";
$email="";
$foto="";
function validacion($datos){
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}


// VALIDACIONES
$errorNombre = "";
$errorApellido = "";
$errorEmail = "";
$errorGenero = "";
$error = false;
$var = "";
$baseEnArray = '';
  if(isset($_POST["enviar"])  &&  $_SERVER["REQUEST_METHOD"] == "POST") {

 //nombre

    if (empty($_POST["nombre"])) {
        $errorNombre = " *Completá el nombre";
        $error = true;
  } else if(strlen($_POST["nombre"]) < 2){
         $errorNombre = " *El nombre tiene que tener dos o más caracteres";
         $error = true;
  }
  //apellido
   if(empty($_POST["apellido"])){
       $errorApellido = "*Completá el apellido";
       $error = true;
   }else if(!ctype_alpha($_POST["apellido"])){
       $errorApellido = "*El apellido sólo debe contener letras";
       $error = true;
   }else if(strlen($_POST["apellido"]) < 2){
    $errorApellido = " *El apellido tiene que tener dos o más caracteres";
    $error = true;
   }

   //email
    if(empty($_POST["email"])){
        $errorEmail = "*Ingresa tu email";
        $error = true;
    }else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $errorEmail = "*El formato es inválido";
        $error = true;
}
    //genero
    if(empty($_POST["genero"])){
        $errorGenero = "*Elija su pais de residencia";
        $error = true;
    }

    if($error != true){
        $nombre = validacion($_POST["nombre"]) ;
        $apellido = validacion($_POST["apellido"]) ;
        $genero = validacion($_POST["genero"]) ;
        $email = validacion($_POST["email"]);
    
    
        $JsonBase= file_get_contents("usuarios.json");
        $baseEnArray= json_decode($JsonBase,true);


        $SESSION= [
        "nombre"=> $nombre,
        "apellido" => $apellido,
        "email"=> $email,
        "genero" => $genero
                                ];

        $baseEnArray[] = $SESSION;
        $nuevaListaDeUsuariosEnJson= json_encode($baseEnArray);
        file_put_contents('usuarios.json',$nuevaListaDeUsuariosEnJson);
        }

      }
// var_dump($baseEnArray);

// var_dump($_POST);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <title>Practica Tower Travel</title>
</head>
<body>

    
    <h2><i class="fab fa-wpforms"></i> Formulario</h2>
    <main>
    <form id="formulario" method="POST" action="index.php" >
    <div id="contenedor-formulario">
    <p class="form">
        <label for="nombre">Nombre: </label>
        <input type="text" name="nombre" id="nombre" value="<?=$nombre ?>" required> <?= $errorNombre ?>
    </p>

    <p class="form">
        <label for="apellido">Apellido: </label>
        <input type="text" name="apellido" id="apellido" value="<?= $apellido?>" required><?= $errorApellido ?>
    </p>
    <p class="form">
        <label for="email"> Email: </label> 
        <input type="text" id="email"name="email" value="<?= $email ?>" required><i class="fas fa-at"></i> <br> <?= $errorEmail ?>
    </p>

    <p class="form">
        <label for="genero"> Genero: </label>
        <select name="genero" id="genero"  required> <?= $errorGenero ?>
        <option selected> Seleccione </option> 
           <option value="F" name="femenino">Femenino</option>
           <option value="M" name="masculino">Masculino</option>
        </select>
    </p>
  
    <input type="submit" id="enviar" name="enviar" value="Enviar"> <i class="far fa-paper-plane"></i>
</div>
</form>
<div class="tablas">
<h2>Tabla todos los usuarios</h2>
<table class="tabla">
  <tr>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Correo</th>
    <th>Genero</th>
  </tr>
  <?php if(is_array($baseEnArray) || is_object($baseEnArray)){
 foreach($baseEnArray as $key) { ?>
    <tr>
        <td> <?php echo $key['nombre'] ?> </td>
        <td> <?php echo $key['apellido'] ?> </td>
        <td class="email"> <?php echo $key['email'] ?> </td>
        <td> <?php echo $key['genero'] ?> </td>
    </tr>   <?php } ?>

  <?php } ?>
 
</table>
<br>

<h2>Tabla género femenino</h2>
<table class="tabla-generoFem">

<tr>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Correo</th>
    <th>Genero</th>
  </tr>
  <?php if(is_array($baseEnArray) || is_object($baseEnArray)){
    foreach ($baseEnArray as $key) {
        if($key['genero'] == 'F'){ ?>
            <tr>
            <td> <?php echo $key['nombre'] ?> </td>
            <td> <?php echo $key['apellido'] ?> </td>
            <td class="email"> <?php echo $key['email'] ?> </td>
            <td> <?php echo $key['genero'] ?> </td>
        </tr> 
       <?php } ?>
  <?php  }  ?>
   <?php     } ?>
</table>

<br>

<h2>Tabla genero masculino</h2>
<table class="tabla-generoMasc">

<tr>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Correo</th>
    <th>Genero</th>
  </tr>
  <?php if(is_array($baseEnArray) || is_object($baseEnArray)){
     foreach ($baseEnArray as $key) {
        if($key['genero'] == 'M'){ ?>
            <tr>
            <td> <?php echo $key['nombre'] ?> </td>
            <td> <?php echo $key['apellido'] ?> </td>
            <td class="email"> <?php echo $key['email'] ?> </td>
            <td> <?php echo $key['genero'] ?> </td>
        </tr> 
       <?php } ?>
  <?php  }  ?>
     <?php   } ?>
</table>
        </div>
</main>


</body>
</html>