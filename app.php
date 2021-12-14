<?php

    //class dashboard
    class Dashboard {
        public $data_inicio;
        public $data_final;
        public $numeroVendas;
        public $totalVendas;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $valor) {
            $this->$attr = $valor;
            return $this;
        }
    }

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

    //class (model)
    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas() {
            $query = 'select count(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_final';

            $stmt = $this->conexao->prepare($query);

            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_final', $this->dashboard->__get('data_final'));

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas() {
            $query = 'select SUM(total) as total_vendas from tb_vendas where data_venda between :data_inicio and :data_final';

            $stmt = $this->conexao->prepare($query);

            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_final', $this->dashboard->__get('data_final'));

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }
    }

    $dashboard = new Dashboard();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    $dia_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    $dashboard->__set('data_inicio', $ano. '-' .$mes. '-01');
    $dashboard->__set('data_final', $ano. '-' .$mes. '-' .$dia_do_mes);

    $conexao = new Conexao();

    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());

    echo json_encode($dashboard);
?>