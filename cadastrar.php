<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: *");

    include_once 'conexao.php';

    $response_json = file_get_contents("php://input");
    $dadosCad = json_decode($response_json, true);

    if($dadosCad){
        if( ){
            $verify = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($verify);
            $stmt->bindParam(':email',$dadosCad['email']);
            $stmt->execute();
            if(($stmt) AND ($stmt->rowCount() < 1)){
                $sql = "INSERT INTO users (id, nome, email, senha) VALUES (0, :nome, :email, :senha)";
                $dadosCad['senha'] = password_hash($dadosCad['senha'], PASSWORD_BCRYPT);
                $cmd = $conn->prepare($sql);
                $cmd->bindParam(':nome', $dadosCad['nome'], PDO::PARAM_STR);
                $cmd->bindParam(':email', $dadosCad['email'], PDO::PARAM_STR);
                $cmd->bindParam(':senha', $dadosCad['senha'], PDO::PARAM_STR);
                $cmd->execute();
                if($cmd->rowCount()){
                    $response = [
                        "erro" => false,
                        "messagem" => "Usuario cadastrado"
                    ];        
                }else{
                    $response = [
                        "erro" => true,
                        "messagem" => "Usuario nao cadastrado"
                    ];
                }
            } else {
                $response = [
                    "erro" => true,
                    "messagem" => "Email existente"
                ];
            }
        } else {
            $response = [
                "erro" => true,
                "messagem" => "Senha Invalida"
            ];
        }
    } else{
        $response = [
            "erro" => true,
            "messagem" => "Falha"
        ];
    }
    http_response_code(200);
    echo json_encode($response);
?>