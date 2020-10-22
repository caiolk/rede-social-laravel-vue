<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'texto', 'imagem', 'link', 'data'
    ];

    public function comentarios()
    {
        return $this->hasMany('App\Model\Comentario');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function curtidas()
    {
        return $this->belongsToMany('App\User','curtidas','conteudo_id','user_id');
    }

    public function getDataAttribute($value)
    {
        return date('H\\hi d/m/Y',strtotime($value));
    }
}
