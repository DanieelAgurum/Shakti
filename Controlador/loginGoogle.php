<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Shakti/Modelo/Usuarias.php';
header('Content-Type: application/json; charset=utf-8');

$u = new Usuarias();
$con = $u->conectarBD();

$token = $_POST['credential'] ?? '';
if(!$token){
    echo json_encode(['success'=>false, 'msg'=>'No se recibió token']);
    exit;
}

// Validar token con Google
$response = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=$token");
$data = json_decode($response, true);

if(!isset($data['email'])){
    echo json_encode(['success'=>false,'msg'=>'Token inválido']);
    exit;
}

$email = $data['email'];
$query = mysqli_query($con, "SELECT * FROM usuarias WHERE correo='$email'");
$usuario = mysqli_fetch_assoc($query);

if(!$usuario){
    echo json_encode(['success'=>false,'msg'=>'Usuario no registrado']);
    exit;
}

// Sesión
session_start();
$_SESSION['id_rol'] = $usuario['id_rol'];
$_SESSION['id_usuario'] = $usuario['id_usuario'];

echo json_encode(['success'=>true,'id_rol'=>$usuario['id_rol']]);
