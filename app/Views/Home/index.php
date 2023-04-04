<?php echo $this->extend('Layout/principal'); ?>

<?php echo $this->section('titulo'); ?> <?php echo $titulo; ?> <?php $this->endSection(); ?>

<?php echo $this->section('estilos'); ?> 

<!-- Aqui os estilos da view -->

<?php $this->endSection(); ?>


<?php echo $this->section('conteudo'); ?> 

<!-- Aqui os conteúdo da view -->

<?php $this->endSection(); ?>



<?php echo $this->section('scripts'); ?> 

<!-- Aqui os scripts da view -->

<?php $this->endSection(); ?>