<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
 
    protected $table            = 'usuarios';
    
    protected $returnType       = 'App\Entities\Usuario';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'nome',
        'email',
        'password',
        'reset_hash',
        'reset_expira_em',
        'imagem',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome'          => 'required|min_length[3]|max_length[120]',
        'email'         => 'required|valid_email|max_length[120]|is_unique[usuarios.email,id,{id}]',
        'password'      => 'required|min_length[6]|max_length[255]',
        'password_confirmation'  => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required'      => 'O Nome é obrigatório.',
            'min_length'    => 'O campo nome  deve ter no mínimo 3 caracteres',
            'max_length'    => 'Excedeu o máximo de caracteres para o campo nome!',
            //'is_unique' => 'Sorry. That email has already been taken. Please choose another.',
        ],
        'email' => [
            'required'      => 'O e-mail é obrigatório.',
            'valid_email'   => 'Digite um e-mail em formato válido!',
            'max_length'    => 'Excedeu o máximo de caracteres para o campo e-mail!',
            'is_unique'     => 'Este e-mail já existe, por favor escolha outro!',
        ],
        'password' => [
            'required'      => 'A senha é obrigatória.',
            'min_length'    => 'O campo senha  deve ter no mínimo 6 caracteres',
            'max_length'    => 'Excedeu o máximo de caracteres para o campo senha!',
            //'is_unique' => 'Sorry. That email has already been taken. Please choose another.',
        ],
        'password_confirmation' => [
            'required_with' => 'Por favor confirme a senha',
            'matches'       => 'As senhas precisam combinar.',
        ],
    ];

    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {

            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            // removemos os dadso a serem salvos
            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }

        return $data;
    }
   
}
