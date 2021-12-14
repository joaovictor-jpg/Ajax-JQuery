<?php

    //conexao com o DB
    class Conexao {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar() {
            try {
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                $conexao->exec('set charset utf8');

                return $conexao;
            }catch (PDOException $e) {
                echo '<p>' .$e->getMessage(). '</p>';
            }
        }
    }

    //class dashboard
    class Dashboard {
        public $clienteAtivo;
        public $ClienteInativo;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $valor) {
            $this->$attr = $valor;
            return $this;
        }
    }

    //class (model)
    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getClienteativo() {
            $query = 'select SUM(cliente_ativo) as cliente_ativo from tb_clientes where cliente_ativo = 1 ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->cliente_ativo;
        }

        public function getClienteInativo() {
            $query = 'select count(*) as cliente_inativo from tb_clientes where cliente_ativo = 0 ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->cliente_inativo;
        }
    }

    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('clienteAtivo', $bd->getClienteativo());
    $dashboard->__set('ClienteInativo', $bd->getClienteInativo());

    echo json_encode($dashboard);

?>