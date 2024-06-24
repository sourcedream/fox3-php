<?php

namespace App\Controllers;

use App\Fox3\Controller;

class MDFEController extends Controller {

    public function closeMDFEPage() {
        return $this->view('mdfe/close.php');
    }

    public function closeMDFE() {
        $barcode = $_REQUEST['barcode'];
    }
}