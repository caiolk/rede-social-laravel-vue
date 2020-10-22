<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\ImgBase64Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function CriarUsuario(Request $request)
    {
        $data = $request->all();
 
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

          
        if($validator->fails())
        {
            return ["status" => false, "validacao" => true,"erros" => $validator->errors()];
        }
        $strImagem = "img/default.png";

        $user =  User::create([
            'name' =>   $data['name'],
            'email' =>  $data['email'],
            'password' =>  bcrypt($data['password']),
            'image' => $strImagem
        ]) ;
        //$user->image = asset($user->image);
        $user->token = $user->createToken($user->email)->accessToken;

        return ['status'=> true, "usuario" => $user];
    }
    public function AtualizarUsuario(Request $request)
    {
        
        $user = $request->user();
        $data = $request->all();
   

        if(isset($data['password'])){

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id) ],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            
            $user->password = bcrypt($data['password']);
     
        }else{
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id) ],
            ]);

        }
        
        if($validator->fails())
        {
            return ["status" => false, "validacao" => true,"erros" => $validator->errors()];
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
      
        if(isset($data['image'])&&!strstr($data['image'],'default.png')){

            $validator = Validator::make($data, [
                'image' => [ new ImgBase64Validator ],
            ]);
            
            if($validator->fails())
            {
                return ["status" => false, "validacao" => true,"erros" => $validator->errors()];
            }

            $time = time();
            $diretorioPai = 'perfil';
            $diretorImagem  = $diretorioPai.DIRECTORY_SEPARATOR.$user->id;
            $strExtencao =  substr(strstr(strstr($data['image'],';base64',true),'/'),1);
            $strUrlImage = $diretorImagem.DIRECTORY_SEPARATOR.$time.'.'.$strExtencao;
            $strArquivo = base64_decode(substr(strstr($data['image'],','),1));
           

            if(!file_exists($diretorioPai)){
                mkdir($diretorioPai,0700);
            }
            
            
   
            if($user->image){
                if(file_exists(strstr($user->image,'perfil/'))){

                    unlink(strstr($user->image,'perfil/'));
                }  
            }
    
            if(!file_exists($diretorImagem)){
                mkdir($diretorImagem,0700);
            }
            file_put_contents($strUrlImage,$strArquivo);
            $user->image = $strUrlImage;
        }
       
        
        $user->save();

        //$user->image = asset($user->image);
        $user->token = $user->createToken($user->email)->accessToken;
        
        return ['status'=> true, "usuario" => $user];

    }

    public function ListarUsuario($id,Request $request)
    {
        $user = User::find($id);
      
        if($user){
            
            return ['status'=> true, "detalhesUsuario" => $user ];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }

    public function SeguirAmigo(Request $request)
    {
        $user = $request->user();
        $amigo = User::find($request->id);
        if($amigo && ($user->id != $amigo->id)){
            $user->amigos()->toggle($amigo->id);
            return ['status'=> true, "amigos" => $user->amigos, "seguidores" => $amigo->seguidores->count()];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }

    public function ListarAmigos(Request $request)
    {
        $user = $request->user();
        if($user){
            return ['status'=> true, "amigos" => $user->amigos, "seguindo" => $user->amigos->count(),"seguidores" => $user->seguidores->count() ];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }

    public function ListarAmigosPagina($id,Request $request)
    {
        $user = User::find($id);
        
        $userLogado = $request->user();
       
        if($user){
            return ['status'=> true, "seguindo" => $user->amigos->count() ,"amigoslogado" => $userLogado->amigos ,"seguidores" => $user->seguidores->count()];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }

    public function ListarSeguidores($id,Request $request)
    {
        $user = User::find($id);

        foreach($user->seguidores as $key => $dados){
            $user->seguidores->member_at = date('d/m/Y H:i:s',strtotime($dados->created_at));
        }
     
        if($user){
            return ['status'=> true, "intSeguindo" => $user->amigos->count(), "intSeguidores" => $user->seguidores->count(),"seguidores" => $user->seguidores];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }
    
    public function ListarSeguindo($id,Request $request)
    {
        $user = User::find($id);
        foreach($user->amigos as $key => $dados){
            $user->amigos->member_at = date('d/m/Y H:i:s',strtotime($dados->created_at));
        }
        if($user){
            return ['status'=> true, "seguindo" => $user->amigos, "intSeguindo" => $user->amigos->count(),"intSeguidores" => $user->seguidores->count()];
        }else{
            return ['status'=> false, "erro" => "Esse usuário não existe!"];
        }
    }

}
