<?php

namespace App\Controllers;

use Fox3\Controller;
use Fox3\Helpers\Mysql;

class MDFEController extends Controller {

    //-----------------------------------------------
    // Authenticated routes
    // TODO: Autenticate the user here
    public function listmdfe() {
        $MDFEs = $this->getMFDEs();
        return $this->view('mdfe/list.php', ['dados' => $MDFEs]);
    }

    public function newMDFE() {
        return $this->view('mdfe/new.php');
    }

    public function saveMDFE() {
        $CHAVE = trim($_POST['CHAVE']);
        $PROTOCOLO = trim($_POST['PROTOCOLO']);
        $FILIAL_ID = trim($_POST['FILIAL_ID']);
        $COD_MUNICIPIO = trim($_POST['COD_MUNICIPIO']);
        $STATUS = 'pendente';

        $erros = $this->validateForm($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO);

        if (count($erros) > 0) {
            return $this->view('mdfe/new.php', ['erros' => $erros]);
        }

        $insert = $this->insertMDFE($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS);

        if ($insert <= 0) {
            return $this->view('mdfe/new.php', ['aviso' => 'Falha na inclusão. Contate o suporte']);
        }

        $_SESSION['success']  = 'MDF-e cadastrado com sucesso';
        return $this->redirect('/listar-mdfe');
    }

    //-----------------------------------------------
    // Public routes
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

    //-----------------------------------------------
    // Database Functions

    private function insertMDFE($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS) {
        $con = Mysql::conn();
        $stm = $con->prepare('INSERT INTO mdfes (chave, protocolo, filial_id, cod_municipio, status) VALUES (?, ?, ?, ?, ?)');
        $stm->bind_param('ssiss', $CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS);
        $stm->execute();
        return $stm->affected_rows;
    }

    private function getMFDEs() {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT mdfes.*, filiais.nome AS nome_filial FROM mdfes INNER JOIN filiais ON (filiais.id = mdfes.filial_id)');//TODO: Order by aqui
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function findMdfe(string $barcode) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM mdfes WHERE chave = ?');
        $stm->bind_param('s', $barcode);
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function validateForm($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO) : array {
        $erros = [];

        if (!$CHAVE) {
            $erros['CHAVE'] = 'Campo obrigatório';
        }

        if (!$PROTOCOLO) {
            $erros['PROTOCOLO'] = 'Campo obrigatório';
        }

        if (!$FILIAL_ID) {
            $erros['FILIAL_ID'] = 'Campo obrigatório';
        }

        if (!$COD_MUNICIPIO) {
            $erros['COD_MUNICIPIO'] = 'Campo obrigatório';
        }

        return $erros;
    }
}
