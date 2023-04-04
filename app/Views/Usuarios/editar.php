<?php echo $this->extend('Layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>

<!-- Aqui os estilos da view -->

<?php $this->endSection(); ?>


<!-- Aqui os conteúdo da view -->
<?php echo $this->section('conteudo'); ?>


<div class="row">
    <div class="col-lg-8">
        <div class="block">

            <div class="block-body">

                <div id="response">
                    
                </div>

            <?php echo form_open('/', ['id' => 'form'], ['id' => "$usuario->id"]); ?>

            <?php echo $this->include('Usuarios/_form'); ?> 

                <div class="form-group mt-5 mb-2">

                    <input id="btn-salvar" type="submit" value="salvar" class="btn btn-danger btn-sm mr-2">
                    <a href="<?php echo site_url("usuarios/exibir/$usuario->id"); ?>" class="btn btn-secondary btn-sm ml-2">Voltar</a>
                </div>

            <?php echo form_close(); ?>    

            </div>

        </div>

    </div>
</div>

<?php $this->endSection(); ?>



<?php echo $this->section('scripts'); ?>

<script>
$(document).ready(function(){
    $("#form").on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('usuarios/atualizar'); ?>',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(){
                $("#response").html('');
                $("#btn-salvar").val('Por favor aguarde...');
            },
            success: function(response){
                $("#btn-salvar").val('Salvar');
                $("#btn-salvar").removeAttr("disabled");
                $('[name=csrf_ordem]').val(response.token);

                if(!response.erro){

                    if(response.info){

                        $("#response").html('<div class="alert alert-info">' + response.info + '</div>');

                    }else{
                        //tudo certo com a atualização, redirecionar
                        window.location.href = "<?php echo site_url("usuarios/exibir/$usuario->id"); ?>";
                    }

                }
                if(response.erro){
                    //existe erro de validação
                    $("#response").html('<div class="alert alert-danger">' + response.erro + '</div>'); 

                    if(response.erros_model){
                       $.each(response.erros_model, function(key, value){
                        $("#response").append('<ul class="list-unstyled"><li class="text-danger">'+ value +'</li></ul>');
                       }); 
                    }
                }
            },
            error: function(){
                alert('Ocorreu uma falha ao processar!');
                $("#btn-salvar").val('Salvar');
                $("#btn-salvar").removeAttr("disabled");
            },
        });
    });
    $('#form').submit(function(){
        $(this).find(":submit").attr('disabled', 'disabled');
    });
});
</script>

<?php $this->endSection(); ?>



