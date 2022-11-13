<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: *");

    include_once 'conexao.php';

    $response_json = file_get_contents("php://input");
    $dadosCad = json_decode($response_json, true);

    if($dadosCad){
        if(strlen($dadosCad['descricao']) < 1){
            $dadosCad['descricao'] = "Sem Descrição";
        } else {
            $dadosCad['descricao'] = $dadosCad['descricao'];
        }

        if($dadosCad['email']){
            $verify = "SELECT id FROM users WHERE email = :email";
            $stmt = $conn->prepare($verify);
            $stmt->bindParam(':email', $dadosCad['email'], PDO::PARAM_STR);
            $stmt->execute();
            if(($stmt) AND ($stmt->rowCount() > 0)){
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($data);
                $idAtiv = $id;
            } else {
                $response = [
                    "erro" => true,
                    "messagem" => "Usuario não encontrado"
                ];
            }
        }

        $sql = "INSERT INTO tb_atividade (idatividade, atividade, data, hora, descricao, fkusuario) VALUES (0, :atividade, :data, :hora, :descricao, :fkusuario)";
        $cmd = $conn->prepare($sql);
        $cmd->bindParam(":atividade", $dadosCad['atividade'], PDO::PARAM_STR);
        $cmd->bindParam(":data", $dadosCad['data']);
        $cmd->bindParam(":hora", $dadosCad['hora']);
        $cmd->bindParam(":descricao", $dadosCad['descricao']);
        $cmd->bindParam(":fkusuario", $idAtiv);
        $cmd->execute();
        if($cmd->rowCount()){
            $response = [
                "erro" => false,
                "messagem" => "Atividade cadastrada"
            ];
        } else {
            $response = [
                "erro" => true,
                "messagem" => "Atividade nao cadastrada"
            ];
        }
    }
    http_response_code(200);
    echo json_encode($response);
?>