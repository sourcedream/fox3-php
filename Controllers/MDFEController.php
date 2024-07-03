<?php

namespace App\Controllers;

use App\Services\MDFEService;
use Exception;
use Fox3\Controller;

use NFePHP\Common\Certificate;
use NFePHP\MDFe\Common\Standardize;
use NFePHP\MDFe\Tools;

class MDFEController extends Controller {

    /**
     * @var MDFEService
     */
    private $mdfeService;

    private $newMDFETemplate = 'mdfe/new.php';
    private $closeDFETemplate = 'mdfe/close.php';

    /**
     * UF -> Code relation
     */
    private $ufCodes = array(
        "AC" => "12",
        "AL" => "27",
        "AM" => "13",
        "AP" => "16",
        "BA" => "29",
        "CE" => "23",
        "DF" => "53",
        "ES" => "32",
        "GO" => "52",
        "MA" => "21",
        "MG" => "31",
        "MS" => "50",
        "MT" => "51",
        "PA" => "15",
        "PB" => "25",
        "PE" => "26",
        "PI" => "22",
        "PR" => "41",
        "RJ" => "33",
        "RN" => "24",
        "RO" => "11",
        "RR" => "14",
        "RS" => "43",
        "SC" => "42",
        "SE" => "28",
        "SP" => "35",
        "TO" => "17"
    );

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
        return $this->view($this->closeDFETemplate);
    }

    public function closeMDFE() {
        $barcode = $_REQUEST['barcode'];

        $mdfe = $this->mdfeService->findMdfe($barcode);

        if (is_null($mdfe) || !$mdfe) {
            return $this->view($this->closeDFETemplate, ['aviso' => 'MDF-e não localizado na base de dados']);
        }

        $close_result = $this->sendEncerramento($mdfe['razao_social'], $mdfe['cnpj'], $mdfe['ie'], $mdfe['uf'], $mdfe['chave'], $mdfe['protocolo'], $mdfe['cod_municipio']);

        if ($close_result[0] == false) {
            return $this->view($this->closeDFETemplate, ['aviso' => 'Falha ao encerrar o MDF-e. ' . $close_result[1]]);
        }

        $this->mdfeService->closeMDFE($mdfe['id']);

        $this->redirect('/mdfe-encerrado');
    }

    public function mdfeClosed() {
        return $this->view('mdfe/closed.php');
    }

    protected function sendEncerramento(string $razao_social, string $cnpj, string $ie, string $uf, string $chave, string $nProt, string $cMun) {
        $config = [
            "atualizacao" => date('Y-m-d H:i:s'),
            "tpAmb" => 1,
            "razaosocial" => $razao_social,
            "cnpj" => $cnpj,
            "ie" => $ie,
            "siglaUF" => $uf,
            "versao" => '3.00'
        ];
        
        try {
            $certificate = Certificate::readPfx(
                '',
                ''
            );
        
            $tools = new Tools(json_encode($config), $certificate);
        
            $cUF = $this->ufCodes[$uf];;
            //$dtEnc = 'Y-m-d'; // Opcional, caso nao seja preenchido pegara HOJE
            $resp = $tools->sefazEncerra($chave, $nProt, $cUF, $cMun); //, $dtEnc
        
            $st = new Standardize();
            $std = $st->toStd($resp);
        
            return [true,  $std];
        } catch (Exception $e) {
            return [false, $e->getMessage()];
        }
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
