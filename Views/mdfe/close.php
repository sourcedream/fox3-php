<?php include('Views/header.php'); ?>

<p>
  Escaneie com a câmera o código de barras do MDFE.
</p>

<form method="POST">
  <input type="text" name="barcode" id="barcode">
  <button type="submit" class="btn btn-info">encerrar</button>
  <div id="reader" width="600px" style="width: 600px;height:600px;"></div>
</form>

<!-- <script src="/pub/quagga.min.js"></script> -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  const quaggaConf = {
    inputStream : {
      name : "Live",
      type : "LiveStream",
      constraints: {
                width: { min: 640 },
                height: { min: 480 },
                facingMode: "environment",
                aspectRatio: { min: 1, max: 2 }
            },
      target: document.querySelector('#cam')    // Or '#yourElement' (optional)
    },
    decoder : {
      readers : [   
    'code_128_reader',
    'ean_reader',
    'ean_8_reader',
    'code_39_reader',
    'code_39_vin_reader',
    'codabar_reader',
    'upc_reader',
    'upc_e_reader',
    'i2of5_reader',
    '2of5_reader',
    'code_93_reader'
      ]
    }
  };
/*
  Quagga.init(quaggaConf, function(err) {
      if (err) {
          console.log(err);
          return
      }
      console.log("Initialization finished. Ready to start");
      Quagga.start();
  });

  Quagga.onDetected(function (result) {
    if (result.codeResult.startInfo.error > 0)
      return;

    console.log(result.codeResult);
    document.querySelector('#barcode').value = result.codeResult.code;
  });*/
  // This method will trigger user permissions
  Html5Qrcode.getCameras().then(devices => {
    /**
     * devices would be an array of objects of type:
     * { id: "id", label: "label" }
     */
    if (devices && devices.length) {
      var cameraId = devices[0].id;
      // .. use this to start scanning.
      const html5QrCode = new Html5Qrcode(/* element id */ "reader");
      html5QrCode.start(cameraId, 
        {
          fps: 10,    // Optional, frame per seconds for qr code scanning
          qrbox: { width: 250, height: 250 }  // Optional, if you want bounded box UI
        },
        (decodedText, decodedResult) => {
          // do something when code is read
          document.querySelector('#barcode').value = decodedText;

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

</script>



<?php include('Views/footer.php'); ?>
