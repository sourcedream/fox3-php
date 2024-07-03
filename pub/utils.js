/**
 * Esta função parseia o que for lido da nota. Se for o código de barras direto, OK. Se for 
 * o QRCode pode vir com um link extra que deve ser removido.
 * @param {string} barcode Result of the barcode scanned
 */
function barcodeParser(barcode) {
    if (barcode.indexOf('https://www.sefaz') == 0) {
        return barcode.replace('https://www.sefaz.mt.gov.br/cte/qrcode?chCTe=', '').replace('&tpAmb=1', '');
    } 
    
    if (barcode.indexOf('https://dfe-portal.svrs.rs.gov.br/') == 0) {
        return barcode.replace('https://dfe-portal.svrs.rs.gov.br/mdfe/qrCode?chMDFe=', '').replace('&tpAmb=1', '');
    }

    //-- Se chegar aqui, tento pegar todo e qualquer conteúdo que possua 44 números em sequência
    let regex = /\d{44}/gm;
    let result = barcode.match(regex);
    
    if (result) {
        return result[0];
    }
        
    return barcode;
}

/**
 * 
 * @param {string} dvDestini Div que vai receber o preview
 * @param {string} outputId Input que vai receber o scan result
 */
function scanMDFE(dvDestini, outputId) {
    Html5Qrcode.getCameras().then(devices => {
        /**
         * devices would be an array of objects of type:
         * { id: "id", label: "label" }
         */
        if (devices && devices.length) {
            var cameraId = devices[0].id;
            
            const html5QrCode = new Html5Qrcode(dvDestini);
            html5QrCode.start(cameraId,
            {
                fps: 60,    // Optional, frame per seconds for qr code scanning
                disableFlip: true,
                qrbox: { width: 650, height: 250 }  // Optional, if you want bounded box UI
            },
            (decodedText, decodedResult) => {
                
                decodedText = barcodeParser(decodedText);
                
                document.querySelector(outputId).value = decodedText;

                // Para o scan assim q achar um código de barras
                html5QrCode.stop().then((ignore) => {

                }).catch(err =>{});
            },
            (errorMessage) => {
                console.log(errorMessage);
            })
            .catch((err) => {
            // Start failed, handle it.
            console.log(err);
            });

        }
    }).catch(err => {
        console.error(err);
    });
}
