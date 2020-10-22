<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function comentarios()
    {
        return $this->hasMany('App\Model\Comentario');
    }

    public function conteudos()
    {
        return $this->hasMany('App\Model\Conteudo');
    }

    public function curtidas()
    {
        return $this->belongsToMany('App\Model\Conteudo','curtidas','user_id','conteudo_id');
    }

    public function amigos()
    {
        return $this->belongsToMany('App\User','amigos','user_id','amigo_id');
    }

    public function seguidores()
    {
        return $this->belongsToMany('App\User','amigos','amigo_id','user_id');
    }

    public function getImageAttribute($value)
    {
        return asset($value);
    }
    

    public function getCreatedAtAttribute($value)
    {   
        
       return date('d/m/Y H:i:s',strtotime(date($value)));
    }

    
}
