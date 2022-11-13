<?php 

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: *");

    include_once 'conexao.php';

    $response_json = file_get_contents("php://input");
    $dadosCad = json_decode($response_json, true);

    if($dadosCad){
        $sql = "SELECT id FROM users WHERE email = :email";
        $cmd = $conn->prepare($sql);
        $cmd->bindParam(':email',$dadosCad['email']);
        $cmd->execute();
        if(($cmd) AND ($cmd->rowCount() > 0)){
            $data = $cmd->fetch(PDO::FETCH_ASSOC);
            extract($data);
            $idAtiv = $id;
            $listar = "SELECT idatividade, atividade, data, hora, descricao FROM tb_atividade WHERE fkusuario = :fkusuario ORDER BY data";
            $vrf_list = $conn->prepare($listar);
            $vrf_list->bindParam(':fkusuario', $idAtiv);
            $vrf_list->execute();
            if(($vrf_list) AND ($vrf_list->rowCount() != 0)){
                while($row_atividade = $vrf_list->fetch(PDO::FETCH_ASSOC)){
                    extract($row_atividade);
                    $lista_atividades["records"][$idatividade] = [
                        'idatividade' => $idatividade,
                        'atividade' => $atividade,
                        'data' => $data,
                        'hora' => $hora,
                        'descricao' => $descricao
                    ];
                }
            }
        }
    }
    http_response_code(200);
    echo json_encode($lista_atividades);
?>