<?php echo $this->extend('Layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php $this->endSection(); ?>

<?php echo $this->section('estilos'); ?> 

<!-- Aqui os estilos da view -->
<link rel="stylesheet" href="<?php echo site_url('recursos/'); ?>vendor/sweetalert/sweetalert2.min.css">   


<?php $this->endSection(); ?>


<!-- Aqui os conteúdo da view -->
<?php echo $this->section('conteudo'); ?> 


<div class="row">
    <div class="col-lg-8">
        <div class="block">
            
            <div class="text-center">
                <?php if($usuario->imagem == null): ?>
                    <img src="<?php echo site_url('recursos/img/usuario_sem_imagem.png'); ?>" class="card-img-top" style="width: 90%;" alt="Usuario sem imagem">
                <?php else: ?> 
                    <img src="<?php echo site_url("usuarios/imagem/$usuario->imagem"); ?>" class="card-img-top" style="width: 90%;" alt="<?php echo esc($usuario->nome); ?>"> 
                <?php endif; ?>  
                
                <a href="<?php echo site_url("usuarios/editarImagem/$usuario->id"); ?>" class="btn btn-outline-primary btn-sm mt-3">Alterar imagem</a>
            </div>
            <hr class="border-secondary">

            <h5 class="card-title mt-2"><?php echo esc($usuario->nome); ?></h5>
            <p class="card-text"><?php echo esc($usuario->email); ?></p>
            <p class="card-text"><?php echo ($usuario->ativo == true ? '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo' : '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo'); ?></p>
            <p class="card-text">Criado: <?php echo $usuario->criado_em->humanize(); ?></p>
            <p class="card-text">Atualizado: <?php echo $usuario->atualizado_em->humanize(); ?></p>
            <!-- Example single danger button -->
            <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ações
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?php echo site_url("usuarios/editar/$usuario->id"); ?>">Editar usuario</a>
                <!-- <div class="dropdown-divider"></div> -->
               
            </div>
            </div>
           
            <a href="<?php echo site_url("usuarios"); ?>" class="btn btn-secondary ml-2">Voltar</a>  

        </div>
        
    </div>
</div>

<?php $this->endSection(); ?>



<?php echo $this->section('scripts'); ?> 

<!-- Aqui os scripts da view -->
<script src="<?php echo site_url('recursos/'); ?>vendor/sweetalert/sweetalert2.all.min.js"></script>
    <script src="<?php echo site_url('recursos/'); ?>vendor/sweetalert/script.js"></script>

<?php $this->endSection(); ?>