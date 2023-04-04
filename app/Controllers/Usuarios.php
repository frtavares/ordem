<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Usuario;
use Token;
use Services;

class Usuarios extends BaseController
{
    private $usuarioModel;

    public function __construct()
    {
      $this->usuarioModel = new \App\Models\UsuarioModel();  
    }

    public function index()
    {
      $data = [
        'titulo' => 'Listagem usuários',
      ]; 

      return view('Usuarios/index', $data);
    }

    public function recuperaUsuarios(){

        if(!$this->request->isAJAX()){
          return redirect()->back();
        }

        $atributos = [
            'id',
            'nome',
            'email',
            'ativo',
            'imagem',
        ];

        $usuarios = $this->usuarioModel->select($atributos)
                                       ->orderBy('id', 'DESC')
                                       ->findAll();
        $data = [];
        foreach($usuarios as $usuario){

          $data[] = [
              'imagem'  => $usuario->imagem,
              'nome'    => anchor("usuarios/exibir/$usuario->id", esc($usuario->nome), 'title="Exibir usuario '.esc($usuario->nome).' "'),
              'email'   => esc($usuario->email),
              'ativo'   => ($usuario->ativo == true ? '<i class="fa fa-unlock text-success"></i>&nbsp;Ativo' : '<i class="fa fa-lock text-warning"></i>&nbsp;Inativo'),
          ];
        }

        $retorno = [
            'data' => $data,
        ];

        return $this->response->setJSON($retorno);

        // echo '<pre>';
        // print_r($retorno);
        // exit;

    }

    public function criar(){

      $usuario = new Usuario();
      //dd($usuario);
      $data = [
        'titulo' => "Novo usuário ",
        'usuario'=> $usuario,
      ];
      return view('Usuarios/criar', $data);
    }

    public function cadastrar(){

      if(!$this->request->isAJAX()){
        return redirect()->back();
      }

      //envio do hash do token do form
      $retorno['token'] = csrf_hash();

      // recupera o post da requisição
      $post = $this->request->getPost();

      // cria novo objeto entidade usuario
      $usuario = new Usuario($post);


      if($this->usuarioModel->protect(false)->save($usuario)){

        $btnCriar = anchor("usuarios/criar", 'Cadastrar novo registro', ['class' => 'btn btn-danger mt-2']);

        session()->setFlashdata('sucesso',"Registro salvo com sucesso!<br> $btnCriar") ;
        //retorna o ultimo id inserido na tabela
        $retorno['id'] = $this->usuarioModel->getInsertID();

        return $this->response->setJSON($retorno);
      }

      // retorno dos erros de validação
      $retorno['erro'] = 'Por favor verifique os erros de validação abaixo e tente novamente!';
      $retorno['erros_model'] = $this->usuarioModel->errors();

      // retorno para o ajax request
      return $this->response->setJSON($retorno);

    }

    public function exibir(int $id = null){

      $usuario = $this->buscaUsuarioOu404($id);
      $data = [
        'titulo' => "Detalhe do usuário : ".esc($usuario->nome),
        'usuario'=> $usuario,
      ];
      return view('Usuarios/exibir', $data);
    }

    //metodo que recupera o usuario
    private function buscaUsuarioOu404(int $id = null){
      if(! $id || !$usuario = $this->usuarioModel->withDeleted(true)->find($id)){
          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
      }
      return $usuario;
    }

    public function editar(int $id = null){

      $usuario = $this->buscaUsuarioOu404($id);
      $data = [
        'titulo' => "Alterar usuário : ".esc($usuario->nome),
        'usuario'=> $usuario,
      ];
      return view('Usuarios/editar', $data);
    }

    public function atualizar(){

      if(!$this->request->isAJAX()){
        return redirect()->back();
      }

      //envio do hash do token do form
      $retorno['token'] = csrf_hash();

      // recupera o post da requisição
      $post = $this->request->getPost();

      // valida a existencia do registro
      $usuario = $this->buscaUsuarioOu404($post['id']);

      // se não for informada a senha , removemos do $post, se não o hashpassword fara o hash de um string vazio
      if(empty($post['password'])){

        unset($post['password']);
        unset($post['password_confirmation']);
      }
      

      // preenchemos os atributos do usuario com os valores do post
      $usuario->fill($post);

      //verifica se houve alteração
      if($usuario->hasChanged() == false){
        $retorno['info'] = 'Não há dados para serem atualizados!';
        return $this->response->setJSON($retorno);
      }

      if($this->usuarioModel->protect(false)->save($usuario)){
        session()->setFlashdata('sucesso','Registro salvo com sucesso!');
        return $this->response->setJSON($retorno);
      }

      // retorno dos erros de validação
      $retorno['erro'] = 'Por favor verifique os erros de validação abaixo e tente novamente!';
      $retorno['erros_model'] = $this->usuarioModel->errors();

      // retorno para o ajax request
      return $this->response->setJSON($retorno);

    }

    public function editarImagem(int $id = null){

      $usuario = $this->buscaUsuarioOu404($id);
      $data = [
        'titulo' => "Alterando a imagem usuário : ".esc($usuario->nome),
        'usuario'=> $usuario,
      ];
      return view('Usuarios/editar_imagem', $data);
    }

    public function upload(){

      if(!$this->request->isAJAX()){
        return redirect()->back();
      }

      //envio do hash do token do form
      $retorno['token'] = csrf_hash();

      $validacao = service('validation');
      //validar arquivos
      $regras = [
        'imagem' => 'uploaded[imagem]|max_size[imagem,2048]|ext_in[imagem,png,jpg,jpeg,webp]',
        
      ];

      $mensagens =  [   // Errors
        'imagem' => [
            'uploaded' => 'Por favor selecione uma imagem',
            'max_size' => 'Excedeu tamanho máximo! Máximo permitido 2048 Mb',
            'ext_in'   => 'Somente é permitido imagens nos formatos: .png, .jpg, .jpeg e .webp ',
        ],
       
      ];

      $validacao->setRules( $regras, $mensagens);

      if($validacao->withRequest($this->request)->run() == false){
        $retorno['erro'] = 'Por favor verifique os erros de validação abaixo e tente novamente!';
        $retorno['erros_model'] = $validacao->getErrors();
  
        // retorno para o ajax request
        return $this->response->setJSON($retorno);
      }

      // recupera o post da requisição
      $post = $this->request->getPost();

      // valida a existencia do registro
      $usuario = $this->buscaUsuarioOu404($post['id']);

      // imagem recuperada do post  
      $imagem = $this->request->getFile('imagem');

      list($largura, $altura) = getimagesize($imagem->getPathName());

      if($largura < "300" || $altura < "300"){
        $retorno['erro'] = 'Por favor verifique os erros de validação abaixo e tente novamente!';
        $retorno['erros_model'] = ['dimensao' => 'A imagem não pode ser menor que 300 x 300 pixels'];
        return $this->response->setJSON($retorno);
      }

      $caminhoImagem = $imagem->store('usuarios');
      //C:\vhost\ordem\writable\uploads/usuarios/1676321871_485627de331eaa25eca7.jpg
      $caminhoImagem = WRITEPATH . "uploads/$caminhoImagem";

      // podemos manipular a imagem salva no diretório:

      //redimensiona a imagem para 300x300 px center
      service('image')
          ->withFile($caminhoImagem)
          ->fit(300, 300, 'center')
          ->save($caminhoImagem);

       $anoAtual = date('Y');  
    

      //adicionar marca d'agua de texto    
      \Config\Services::image('imagick')
          ->withFile($caminhoImagem)
          ->text("Ordem $anoAtual - User-ID $usuario->id",[
              'color' => '#fff',
              'opacity' => 1,
              'withShadow' => true,
              'hAlign' => 'center',
              'vAlign' => 'bottom',
              'fontSize' => 10,
          ])
          ->save($caminhoImagem);  

      // a partir daqui podemo atualizar a tabela usuarios
      $usuario->imagem = $imagem->getName();
      
      $this->usuarioModel->save($usuario);
      
      session()->setFlashdata('sucesso','Imagem atualizada com sucesso!');
         
      // retorno para o ajax request
      return $this->response->setJSON($retorno);

    }



}
