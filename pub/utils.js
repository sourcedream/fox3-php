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

function detectCameras(dvDestini, outputId, onScan = null) {
    Html5Qrcode.getCameras().then(devices => {
        /**
         * devices would be an array of objects of type:
         * { id: "id", label: "label" }
         */

        if (devices && devices.length) {
            if (devices.length == 1) {
                scanMDFE(devices[0].id, dvDestini, outputId, onScan);
            }

            for (let i = 0; i < devices.length; i++) {
                if (devices[i].label === "Câmera Traseira") {
                    scanMDFE(devices[i].id, dvDestini, outputId, onScan);
                    return;
                }
            }

            montaSelecaoCamera(devices, dvDestini, outputId, onScan);
        }
    });
}

function montaSelecaoCamera(devices, dvDestini, outputId, onScan) {
    const dvRender = document.querySelector('#' + dvDestini);

    dvRender.innerHTML = `
    <label>Escolha uma câmera</label>
    <select id='dbCamera'>
    ${devices.map(d => `<option value="${d.id}">${d.label}</option>`).join('')}
    </select>
    <button id="btnInicarScanGen" type="button" class="btn btn-success">Selecionar iniciar scan</button>`;

    document.querySelector('#btnInicarScanGen').addEventListener('click', () => {
        const cameraId = document.querySelector('#dbCamera').value;
        scanMDFE(cameraId, dvDestini, outputId, onScan);
    });
}

// Variável global (sim eu sei) para gerenciar o acesso ao flash da camera.
let html5QrCode = null;

/**
 * @param {string} cameraId Id do dispositivo de câmera selecionado
 * @param {string} dvDestini Div que vai receber o preview
 * @param {string} outputId Input que vai receber o scan result
 */
function scanMDFE(cameraId, dvDestini, outputId, onScan = null) {
    html5QrCode = new Html5Qrcode(dvDestini);
    
    html5QrCode.start(cameraId,
        {
            fps: 60,    // Optional, frame per seconds for qr code scanning
            //qrbox: { width: 650, height: 250 }  // Optional, if you want bounded box UI
        },
        (decodedText, decodedResult) => {

            decodedText = barcodeParser(decodedText);

            document.querySelector(outputId).value = decodedText;

            // Para o scan assim q achar um código de barras
            html5QrCode.stop().then((ignore) => {
                if (onScan)
                    onScan();
            }).catch(err => { });
        },
        (errorMessage) => {
            console.log(errorMessage);
        })
        .catch((err) => {
            // Start failed, handle it.
            console.log(err);
        });
}

let torchEnabled = false;
function toggleFlash() {
    const constraints = {
        advanced: [{ torch: !torchEnabled }]
    };
    html5QrCode.applyVideoConstraints(constraints).then(() => {
        torchEnabled = !torchEnabled;
    });
}
