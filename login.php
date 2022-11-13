<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");

$retorno = array();
$navegador = $_SERVER["HTTP_USER_AGENT"];
$fim = time() + ((3600*24)*15);

include_once 'conexao.php';

$response_json = file_get_contents("php://input");
$dados = json_decode($response_json, true);

if($dados){
    $sql = "SELECT * FROM users WHERE email = :email";
    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':email', $dados['email']);
    $cmd->execute();
    
    if(($cmd) AND ($cmd->rowCount() > 0)){
        $data = $cmd->fetch(PDO::FETCH_ASSOC);
        extract($data); 
        if(password_verify($dados['senha'], $senha)){
            $response = [
                "erro" => false,
                "messagem" => "Usuario Logado"
            ];
        } else {
            $response = [
                "erro" => true,
                "messagem" => "Dados incorretos"
            ];
        }
    } else {
        $response = [
            "erro" => true,
            "messagem" => "Usuario nao encontrado"
        ];
    }
}

http_response_code(200);
echo json_encode($response);
?>