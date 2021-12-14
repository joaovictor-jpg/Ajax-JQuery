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

    class Dashboard {
        public $reclamacao;
        public $elogios;
        public $sugestoes_melhorias;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $valor) {
            $this->$attr = $valor;
            return $this;
        }
    }

    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getReclamação() {
            $query = 'select sum(tipo_contato) as reclamacao from tb_contatos where tipo_contato = 1 ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->reclamacao;
        }

        public function getElogios() {
            $query = 'select sum(tipo_contato) as elogios from tb_contatos where tipo_contato = 2 ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->elogios;
        }

        public function getSugestao() {
            $query = 'select sum(tipo_contato) as sugestao from tb_contatos where tipo_contato = 3 ';

            $stmt = $this->conexao->prepare($query);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->sugestao;
        }
    }

    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('reclamacao', $bd->getReclamação());
    $dashboard->__set('elogios', $bd->getElogios());
    $dashboard->__set('sugestoes_melhorias', $bd->getSugestao());

    echo json_encode($dashboard);
?>