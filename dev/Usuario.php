<?php

include_once('../php/conexao.php');

class Usuario {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function listarUsuarios() {
        $sql = "SELECT * FROM usuarios";
        $result = $this->conexao->query($sql);
        if (!$result) {
            die("Erro na consulta: " . $this->conexao->error);
        }

        return $result;
    }
}

?>
