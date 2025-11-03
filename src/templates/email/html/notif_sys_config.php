<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <div>
        <img style=" max-height: 70px;margin-left: auto;margin-right: auto" src="cid:logo" alt="logo"  />
    </div>
    <br>
    <p>
        Se configuro en modo "<?= $config_modo == 'real' ? 'PRODUCCIÓN' : 'BETA' ?>" en el host: <a href="<?= $cliente_url ?>"><?= $cliente_url ?></a>
    </p>
    <br>
</div>
