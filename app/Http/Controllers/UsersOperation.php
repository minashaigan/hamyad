<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UsersOperation extends Controller
{
    // Checking the duplicate of Email on registering
//    public function CheckEmail($email)
//    {
//        $user=User::where('email',$email)->first();
//        if(!is_null($user))
//        {
//            return 0;
//        }
//        return 1;
//    }

    public function ChangePass()
    {
        if(!Input::has('Password')|| !Input::has('NewPassword')){
            return redirect('/Profile?error=noinfo');
        }
        $user=\Auth::user();
        if(!password_verify(Input::get('Password'),$user->password))
            return redirect('/Profile?error?wrongpass');
        $user->password=bcrypt(Input::get('NewPassword'));
        $user->save();
    }


    public function test($input ){
    }

    public function RetrieveMyCourses()
    {
        $user=\Auth::user();
        $courses=$user->courses_take;
        foreach ($courses as $course){
            $course['Teachers']="";
            $counter=0;
            foreach ($course->teachers as $teacher){
                if($counter)
                    $course['Teachers']=$course['Teachers'].",".$teacher->name;
                else
                    $course['Teachers']=$teacher->name;
                $counter++;
            }
            $rate_count=0;
            $rate_value=0;
            foreach ($course->rates as $rate){
                $rate_count++;
                $rate_value +=$rate->pivot->rate;
            }
            $count=0;
            $time=0;
            foreach ($course->section as $section){
                $count++;
                $time+=$section->time;
            }
            $course['sections_time']=$time;
            $course['sections_count']=$count;
            $course['rates_value']=$rate_value;
            $course['rates_count']=$rate_count;
            $counter=$course->category->name;

        }
        return $courses;
    }

    public function RetrieveMyPacks()
    {
        
    }
}
