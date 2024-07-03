<?php

namespace App\Services;

use Fox3\Helpers\Mysql;

class MDFEService {
    public function insertMDFE($CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS) {
        $con = Mysql::conn();
        $stm = $con->prepare('INSERT INTO mdfes (chave, protocolo, filial_id, cod_municipio, status) VALUES (?, ?, ?, ?, ?)');
        $stm->bind_param('ssiss', $CHAVE, $PROTOCOLO, $FILIAL_ID, $COD_MUNICIPIO, $STATUS);
        $stm->execute();
        return $stm->affected_rows;
    }

    public function getMFDEs() {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT mdfes.*, filiais.nome AS nome_filial FROM mdfes INNER JOIN filiais ON (filiais.id = mdfes.filial_id)');//TODO: Order by aqui
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function findMdfe(string $barcode) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM mdfes WHERE chave = ?');
        $stm->bind_param('s', $barcode);
        $stm->execute();
        return $stm->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}