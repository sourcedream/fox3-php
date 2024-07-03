<?php

namespace App\Controllers;

use App\Services\MDFEService;
use Fox3\Controller;

class MDFEController extends Controller {

    /**
     * @var MDFEService
     */
    private $mdfeService;

    private $newMDFETemplate = 'mdfe/new.php';

    public function __construct() {
        $this->mdfeService = new MDFEService();
    }

    //-----------------------------------------------
    // Authenticated routes
    public function listmdfe() {
        $mdfeList = $this->mdfeService->getMFDEs();
        return $this->view('mdfe/list.php', ['dados' => $mdfeList]);
    }

    public function newMDFE() {
        return $this->view($this->newMDFETemplate);
    }

    public function saveMDFE() {
        $chave = trim($_POST['CHAVE']);
        $protocolo = trim($_POST['PROTOCOLO']);
        $filial_id = trim($_POST['FILIAL_ID']);
        $cod_municipio = trim($_POST['COD_MUNICIPIO']);
        $status = 'pendente';

        $erros = $this->validateForm($chave, $protocolo, $filial_id, $cod_municipio);

        if (count($erros) > 0) {
            return $this->view($this->newMDFETemplate, ['erros' => $erros]);
        }

        $insert = $this->mdfeService->insertMDFE($chave, $protocolo, $filial_id, $cod_municipio, $status);

        if ($insert <= 0) {
            return $this->view($this->newMDFETemplate, ['aviso' => 'Falha na inclusão. Contate o suporte']);
        }

        $_SESSION['success']  = 'MDF-e cadastrado com sucesso';
        $this->redirect('/listar-mdfe');
    }

    //-----------------------------------------------
    // Public routes
    public function closeMDFEPage() {
        return $this->view('mdfe/close.php');
    }

    public function closeMDFE() {
        $barcode = $_REQUEST['barcode'];

        $mdfe = $this->mdfeService->findMdfe($barcode);

        if (is_null($mdfe) || !$mdfe) {
            return $this->view('mdfe/close.php', ['aviso' => 'MDF-e não localizado na base de dados']);
        }

        $this->redirect('/mdfe-encerrado');
    }

    public function mdfeClosed() {
        return $this->view('mdfe/closed.php');
    }

    private function validateForm($chave, $protocolo, $filial_id, $cod_municipio) : array {
        $erros = [];
        $required_field_message = 'Campo obrigatório';

        if (!$chave) {
            $erros['CHAVE'] = $required_field_message;
        }

        if (!$protocolo) {
            $erros['PROTOCOLO'] = $required_field_message;
        }

        if (!$filial_id) {
            $erros['FILIAL_ID'] = $required_field_message;
        }

        if (!$cod_municipio) {
            $erros['COD_MUNICIPIO'] = $required_field_message;
        }

        return $erros;
    }
}
