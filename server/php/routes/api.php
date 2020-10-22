<?php

use Illuminate\Http\Request;
use App\User;
use App\Model\Conteudo;
use App\Model\Comentario;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login','Auth\LoginController@Login');
Route::post('/cadastro','User\UserController@CriarUsuario');

Route::group(['prefix' => 'conteudo'],function(){
    Route::middleware('auth:api')->post('/adicionar','Publicacao\PublicacaoController@CriarComentario');
    Route::middleware('auth:api')->get('/listar','Publicacao\PublicacaoController@ListarConteudos');
    Route::middleware('auth:api')->put('/curtir/{id}','Publicacao\PublicacaoController@CurtirPublicacao');
    Route::middleware('auth:api')->put('/comentar/{id}','Publicacao\PublicacaoController@ComentarPublicacao');
    Route::middleware('auth:api')->put('/curtirpagina/{id}','Publicacao\PublicacaoController@CurtirPublicacaoPagina');
    Route::middleware('auth:api')->put('/comentarpagina/{id}','Publicacao\PublicacaoController@ComentarPublicacaoPagina');
    Route::middleware('auth:api')->get('/pagina/listar/{id}','Publicacao\PublicacaoController@ListaPaginaPerfil');
    
});

Route::middleware('auth:api')->put('/perfil','User\UserController@AtualizarUsuario');
Route::group(['prefix' => '/usuario'],function(){ 
    Route::middleware('auth:api')->get('/detalheusuario/{id}','User\UserController@ListarUsuario'); 
    Route::middleware('auth:api')->post('/amigo','User\UserController@SeguirAmigo');
    Route::middleware('auth:api')->get('/listaamigos','User\UserController@ListarAmigos');
    Route::middleware('auth:api')->get('/listaamigospagina/{id}','User\UserController@ListarAmigosPagina'); 
    Route::middleware('auth:api')->get('/listaseguidores/{id}','User\UserController@ListarSeguidores'); 
    Route::middleware('auth:api')->get('/listaseguindo/{id}','User\UserController@ListarSeguindo'); 
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/testes',function(){
    /*
    $user = User::find(1);
    $user2 = User::find(3);
    $conteudo = Conteudo::find(13);
    $user2->comentarios()->create([
        'conteudo_id' => $conteudo->id,
        'texto' => 'AAAAAA', 
        'data' => date('Y-m-d')

    ]); 
    
     $user->conteudos()->create([
        'titulo' => 'Conteudo 1',
        'texto' => 'Texto 1', 
        'imagem' => 'Imagem 1', 
        'link' => 'Link', 
        'data' => date('Y-m-d')

    ]);  */
    //$user->amigos()->attach($user2->id);
    //$user->amigos()->detach($user2->id);    
    //$user->amigos()->toggle($user2->id);
    //$user->curtidas()->toggle($conteudo->id);
/*
     $user->comentarios()->create([
        'conteudo_id' => $conteudo->id,
        'texto' => 'Hu3', 
        'data' => date('Y-m-d')

    ]); 
    
    $user2->comentarios()->create([
        'conteudo_id' => $conteudo->id,
        'texto' => 'AAAAAA', 
        'data' => date('Y-m-d')

    ]); 
    return $user->conteudos([
        'titulo' => 'Conteudo 1',
        'texto' => 'Texto 1', 
        'imagem' => 'Imagem 1', 
        'link' => 'Link', 
        'data' => date('Y-m-d')

    ])->save(); */

}); 
