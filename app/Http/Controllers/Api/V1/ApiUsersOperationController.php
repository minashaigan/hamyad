<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\UsersOperation;
use App\Http\Requests\StoreSubscribesRequest;
use App\Http\Requests\UpdateSubscribesRequest;
use App\Subscribe;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

#TODO
class ApiUsersOperationController extends Controller
{

    protected $usersOperation_controller ;

    public function __construct(UsersOperation $item)
    {
        $this->usersOperation_controller = $item;
    }

    public function ChangePass()
    {
        $response = ['result' => '0'];

        if(!Input::has('Password')|| !Input::has('NewPassword')){
            return $response;
        }

        $n = Input::get('api_token');
        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;

        if(!password_verify(Input::get('Password'),$user->password))
            return $response;
        $user->password=bcrypt(Input::get('NewPassword'));
        if($user->save()){
            $response['result'] = 1;
            return $response;
        }
        else
            return $response;
    }

    public function UploadPhoto()
    {

        $response = ['result' => '0'];
        $n = Input::get('api_token');

        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;

        if (Input::hasFile('image')) {
            $file = array('image' => Input::file('image'));
            $rules = array('image' => 'required|max:10000|mimes:jpeg,JPEG,PNG,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                return $response;
            }
            if (Input::file('image')->isValid()) {
                $destinationPath = 'uploads/'.$user->id.'/'; // upload path
                $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111,99999).'.'.$extension; // renameing image
                Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

                $user->image=$destinationPath.'/'.$fileName;
                try{
                    $user->save();
                }
                catch ( \Illuminate\Database\QueryException $e){
                    return $response;
                }
                $response['result']='1';
                return $response;
            }
            else {
                return $response;
            }
        }
        else
            return $response;
    }

    public function ChangeInfo()
    {
        $response = ['result' => '0'];
        $n = Input::get('api_token');

        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;

        if (Input::has('Name')) {
            $user->name = Input::get('Name');
            try{
                $user->save();
            }
            catch ( \Illuminate\Database\QueryException $e){
                return $response;
            }
            $response['result']='1';
            return $response;
        }
        else
            return $response;
    }

    public function RetrieveCourses()
    {
        $response = ['result' => '0'];
        $n = Input::get('api_token');

        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;
        return $this->usersOperation_controller->RetriveCourseHelper($user);
    }

    public function RetrieveMyPack()
    {
        $response = ['result' => '0'];
        $n = Input::get('api_token');

        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;
        return $user->pack_take;
    }

    public function RetrieveFavorite()
    {
        $response = ['result' => '0'];
        $n = Input::get('api_token');

        $user = User::where('api_token', $n)->first();
        if(!is_null($user))
            $user=User::find($user->id);
        else
            return $response;
        return $this->usersOperation_controller->RetrieveFaveHelper($user);
    }

}