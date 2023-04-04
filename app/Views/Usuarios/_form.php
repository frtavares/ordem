<div class="form-group">
    <label class="form-control-label">Nome completo</label>
    <input type="text" name="nome" placeholder="insira o nome completo" class="form-control" value="<?php echo esc($usuario->nome); ?>" >
</div>

<div class="form-group">
    <label class="form-control-label">E-mail</label>
    <input type="email" name="email" placeholder="insira o email" class="form-control" value="<?php echo esc($usuario->email); ?>" >
</div>

<div class="form-group">
    <label class="form-control-label">Senha</label>
    <input type="password" name="password" placeholder="insira a senha" class="form-control">
</div>

<div class="form-group">
    <label class="form-control-label">Confirmação de Senha</label>
    <input type="password" name="password_confirmation" placeholder="confirme a senha" class="form-control">
</div>

<div class="custom-control custom-control">
    <input type="hidden" name="ativo" value="0">
    <input type="checkbox" name="ativo" value="1" class="custom-control-input" id="ativo" <?php if($usuario->ativo == true): ?> checked <?php endif; ?> >
    <label class="custom-control-label" for="ativo">Usuário ativo</label>
</div>
