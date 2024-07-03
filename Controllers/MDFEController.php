<?php

namespace App\Controllers;

use App\Services\MDFEService;
use Fox3\Controller;

class MDFEController extends Controller {

    /**
     * @var MDFEService
     */
    private $MDFEservice;

    public function __construct() {
        $this->MDFEservice = new MDFEService();
    }

    //-----------------------------------------------
    // Authenticated routes
    public function listmdfe() {
        $MDFEs = $this->MDFEservice->getMFDEs();
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

        $insert = $this->MDFEservice->insertMDFE($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS);

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

        $mdfe = $this->MDFEservice->findMdfe($barcode);

        if (is_null($mdfe) || !$mdfe) {
            return $this->view('mdfe/close.php', ['aviso' => 'MDF-e não localizado na base de dados']);
        }        

        return $this->redirect('/mdfe-encerrado');
    }

    public function mdfeClosed() {
        return $this->view('mdfe/closed.php');
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
