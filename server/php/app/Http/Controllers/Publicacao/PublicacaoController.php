<?php

namespace App\Http\Controllers\Publicacao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Model\Conteudo;
use App\User;

class PublicacaoController extends Controller
{
    public function CriarComentario(Request $request)
    {
        $data = $request->all();
        $user = $request->user();
       
        $validator = Validator::make($data,[
            'titulo' => 'required',
            'texto' => 'required'
        ]);
        if($validator->fails())
        {
            return ["status" => false, "validacao" => true,"erros" => $validator->errors()];
        }

        $conteudo = new Conteudo;
        $conteudo->titulo = $data['titulo'];
        $conteudo->texto = $data['texto'];
        $conteudo->link = $data['link'] ? $data['link'] : '#';
        $conteudo->imagem = $data['imagem'] ? $data['imagem'] : '#';
        $conteudo->data = date('Y-m-d H:i:s');
        
        $user->conteudos()->save($conteudo);
        $conteudos = Conteudo::with('user')->orderBy('data','DESC')->paginate(5);

        return ['status' => true, 'conteudos' => $conteudos];
        
    }

    public function ListarConteudos(Request $request)
    {   
        $user = $request->user();
        $amigos = $user->amigos()->pluck('id');
        $amigos->push($user->id);

        $conteudos = Conteudo::whereIn('user_id',$amigos)->with('user')->orderBy('data','DESC')->paginate(5);
        

        foreach($conteudos as $key => $conteudo){
            $conteudo->total_curtidas = $conteudo->curtidas()->count();
            $conteudo->comentarios = $conteudo->comentarios()->with('user')->get();
            $obCurtir = $user->curtidas()->find($conteudo->id);
            if($obCurtir){
                $conteudo->blCurtir = true;
            }else{
                $conteudo->blCurtir = false;
            }
        } 

        return ['status' => true, 'conteudos' => $conteudos];
       
    }

    public function CurtirPublicacao($id,Request $request)
    {
        
        $conteudo = Conteudo::find($id);
        if($conteudo){
            $user = $request->user();
            $user->curtidas()->toggle($conteudo->id);

            return [
                    'status' => true, 
                    'curtidas' => $conteudo->curtidas()->count(),
                    'lista' => self::ListarConteudos($request) ];
        }else{
            return ['status' => false, 'erro' => "Conteúdo não existe"];
        }
        
    }

    public function ComentarPublicacao($id,Request $request)
    {
        
        $conteudo = Conteudo::find($id);
        if($conteudo){
            $user = $request->user();
            $user->curtidas()->toggle($conteudo->id);

            $user->comentarios()->create([
                'conteudo_id' => $conteudo->id,
                'texto' => $request->texto, 
                'data' => date('Y-m-d H:i:s')
        
            ]); 

            return [
                    'status' => true, 
                    'lista' => self::ListarConteudos($request) ];
        }else{
            return ['status' => false, 'erro' => "Conteúdo não existe"];
        }
        
    }

    public function CurtirPublicacaoPagina($id,Request $request)
    {
        
        $conteudo = Conteudo::find($id);
        if($conteudo){
            $user = $request->user();
            $user->curtidas()->toggle($conteudo->id);

            return [
                    'status' => true, 
                    'curtidas' => $conteudo->curtidas()->count(),
                    'lista' => self::ListaPaginaPerfil($conteudo->user_id,$request) ];
        }else{
            return ['status' => false, 'erro' => "Conteúdo não existe"];
        }
        
    }

    public function ComentarPublicacaoPagina($id,Request $request)
    {
        
        $conteudo = Conteudo::find($id);
        if($conteudo){
            $user = $request->user();
            $user->curtidas()->toggle($conteudo->id);

            $user->comentarios()->create([
                'conteudo_id' => $conteudo->id,
                'texto' => $request->texto, 
                'data' => date('Y-m-d H:i:s')
        
            ]); 

            return [
                    'status' => true, 
                    'lista' => self::ListaPaginaPerfil($conteudo->user_id,$request) ];
        }else{
            return ['status' => false, 'erro' => "Conteúdo não existe"];
        }
        
    }

    public function ListaPaginaPerfil($id,Request $request)
    {
        $obDonoPagina = User::find($id);
      
        if($obDonoPagina){

            $conteudos = $obDonoPagina->conteudos()->with('user')->orderBy('data','DESC')->paginate(5);
            $user = $request->user();

            foreach($conteudos as $key => $conteudo){
                $conteudo->total_curtidas = $conteudo->curtidas()->count();
                $conteudo->comentarios = $conteudo->comentarios()->with('user')->get();
                $obCurtir = $user->curtidas()->find($conteudo->id);
                if($obCurtir){
                    $conteudo->blCurtir = true;
                }else{
                    $conteudo->blCurtir = false;
                }
            } 

            return ['status' => true, 'conteudos' => $conteudos,"owner"=>$obDonoPagina];
        }else{
            return ['status' => false, 'erro' => "Opss!!Página não encontrada!"];
        }
        
        
    }
}
