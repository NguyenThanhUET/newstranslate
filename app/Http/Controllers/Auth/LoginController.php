<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('login/index');
    }

    /**
     * member login
     */
    public function memberLogin(){

    }
    /**
     * admin login
     */
    public function adminLogin(){
        return view('login/admin');
    }
    public function googleLogin(Request $request){
        $this->googleLoginHandler($request);
    }
    protected function googleLoginHandler($request){
        $google_redirect_url = route('user.googleLogin');
        $gClient = new \Google_Client();
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setScopes(array(
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ));
        $google_oauthV2 = new \Google_Service_Oauth2($gClient);
        if ($request->get('code')){
            $gClient->authenticate($request->get('code'));
        }
        if ($gClient->getAccessToken()){
            //For logged in user, get details from google using access token
            $guser = $google_oauthV2->userinfo->get();
            print_r($guser);
            //check exists and register or login user
            $email          =   $guser->email;
            //$id             =   $guser->id;
            //$name           =   $guser->name;
            $firstName      =   $guser->givenName;
            $lastName       =   $guser->familyName;
            $gender         =   $guser->gender;
            $imageUrl       =   $guser->imageUrl;//https://lh4.googleusercontent.com/-oQsOauUtSqM/AAAAAAAAAAI/AAAAAAAAADE/h5fXSTJMOEs/s96-c/photo.jpg
            $account        =   DB::table('tbl_account')->where('email',$email)->first();
            $profile_url = route('user.user_profile');
            if(isset($account)){
                header('Location: '.$profile_url);
                die();
            }else{
                $account    =   array(
                    'email' => $email,
                    'password'=>$random_string = md5(microtime()),
                    'created_user'=>'google',
                    'created_at'=>date('Y-m-d')
                );
                DB::table('tbl_account')->insert($account);
                $newAccount = DB::table('tbl_account')->where('email',$email)->first();
                $userInfor    =   array(
                    'id' => $newAccount->id,
                    'email'=>$email,
                    'first_name'=>$firstName,
                    'last_name'=>$lastName,
                    'image_url'=>$imageUrl,
                    'gender'=>$gender,
                    'address'=>''
                );
                DB::table('tbl_user_infor')->insert($userInfor);
                //return redirect()->route('user.user_profile');
                header('Location: '.$profile_url);
                die();
            }

        } else{
            //For Guest user, get google login url
            $authUrl = $gClient->createAuthUrl();
            header('Location: '.$authUrl);
            die();
        }
    }
}
