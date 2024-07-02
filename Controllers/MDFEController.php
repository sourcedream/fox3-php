<?php

namespace App\Controllers;

use App\Fox3\Controller;
use App\Fox3\Helpers\Mysql;

class MDFEController extends Controller {

    public function closeMDFEPage() {
        return $this->view('mdfe/close.php');
    }

    public function closeMDFE() {
        $barcode = $_REQUEST['barcode'];

        $mdfe = $this->findMdfe($barcode);

        if (is_null($mdfe) || !$mdfe) {
            return $this->view('mdfe/close.php', ['aviso' => 'MDF-e não localizado na base de dados']);
        }        

        return $this->redirect('/mdfe-encerrado');
    }

    public function mdfeClosed() {
        return $this->view('mdfe/closed.php');
    }

    private function getMFDEs() {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM MDFE');
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function findMdfe(string $barcode) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM MDFE WHERE chave = ?');
        $stm->bind_param('s', $barcode);
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
