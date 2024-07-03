<?php

namespace App\Services;

use Fox3\Helpers\Mysql;

class MDFEService {
    public function insertMDFE($chave, $protocolo, $filial_id, $cod_municipio, $status) {
        $con = Mysql::conn();
        $stm = $con->prepare('INSERT INTO mdfes (chave, protocolo, filial_id, cod_municipio, status) VALUES (?, ?, ?, ?, ?)');
        $stm->bind_param('ssiss', $chave, $protocolo, $filial_id, $cod_municipio, $status);
        $stm->execute();
        return $stm->affected_rows;
    }

    public function getMFDEs() {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT mdfes.*, filiais.nome AS nome_filial FROM mdfes INNER JOIN filiais ON (filiais.id = mdfes.filial_id) ORDER BY id DESC');
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findMdfe(string $barcode) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT mdfes.*, filiais.cnpj, filiais.ie, filiais.uf, filiais.nome as razao_social FROM mdfes INNER JOIN filiais ON (filiais.id = mdfes.filial_id) WHERE chave = ?');
        $stm->bind_param('s', $barcode);
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function closeMDFE(int $id) {
        $con = Mysql::conn();
        $stm = $con->prepare('UPDATE mdfes SET status = ? WHERE id = ?');
        $stm->bind_param('si', $id, 'encerrado');
        $stm->execute();
        return $stm->affected_rows;
    }
}
