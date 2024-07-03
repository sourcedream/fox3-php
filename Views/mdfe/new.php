<?php include_once 'Views/header.php'; ?>


<div class="container">

    <h1>Novo MDFE</h1>

    <form method="POST">
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="CHAVE" name="CHAVE" aria-describedby="Chave do MDFE" required placeholder="Chave">
            <button class="btn btn-info" type="button" onclick="startScan()">Escanear Chave</button>
            <button class="btn btn-success" type="button" onclick="toggleFlash()">Ligar Flash</button>
        </div>

        <div class="mb-3" id='reader' width="800px" style="display:none; width: 800px;height:600px; margin-top: 10px; left: calc(50% - 400px); border:1px solid black; text-align:center"></div>

        <div class="mb-3">
            <label for="PROTOCOLO" class="form-label">Protocolo</label>
            <input type="text" class="form-control" id="PROTOCOLO" name="PROTOCOLO" aria-describedby="Protocolo de Autorização do MDFE" required>
            <div id="PROTOCOLO" class="form-text">Protocolo de autorização do MDFE</div>
        </div>

        <div class="mb-3">
            <label for="FILIAL" class="form-label">Filial</label>
            <select class="form-control" id="FILIAL_ID" name="FILIAL_ID" name="CHAVE">
                <option value="" disabled selected>Selecione a filial</option>
                <option value="1">Matriz</option>
                <option value="2">Filial</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="COD_MUNICIPIO" class="form-label">Código Município</label>
            <input type="text" class="form-control" id="COD_MUNICIPIO" name="COD_MUNICIPIO" aria-describedby="Município do MDFE" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/listar-mdfe" class="btn btn-danger">Cancelar</a>
    </form>
</div>

<script>
    function startScan() {
        const dvScan = 'reader';
        const elementReader = document.querySelector('#' + dvScan);
        elementReader.style.display = '';
        detectCameras(dvScan, '#CHAVE', () => elementReader.style.display = 'none');
    }
</script>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<?php include_once 'Views/footer.php'; ?>
