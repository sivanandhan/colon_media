<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Media;
use Carbon\Carbon;

class MediaController extends Controller
{
    protected $media;
    public function __construct(Media $media){
        $this->media = $media;
    }
    public function AddMedia(Request $request){
        $employee_model = new Media;
        $validated = Validator::make($request->all(),[
            'name' => 'required|unique:posts|max:255',
            'phone'=> 'required|phone:posts|max:20',            
            'email'=> 'required|email:rfc,dns',            
            'department'=> 'required|unique:posts|max:255',            
            'designation'=> 'required|unique:posts|max:255',            
        ]);
        $result = ['status'=>200];
        $employee_model->title=$request->title;
        /* $employee_model->file=$request->file; */
        $employee_model->publish_type=$request->publish_type;
        $allowedfileExtension=["gif", "svg", "png","jpg","jpeg"];
        $file_d = $request->file('file');
        if (!empty($file_d)) {
          $filename = $file_d->getClientOriginalName();
          $filedata = file_get_contents($file_d->getRealPath());
          $filedata = $file_d->getRealPath();
          $extension = $file_d->getClientOriginalExtension();
          $check=in_array($extension,$allowedfileExtension);
          if($check){
              $FileID = ($request->FileID=='undefined' || $request->FileID=='null' || $request->FileID=='') ? 0 : $request->FileID;
              $name = Carbon::now()->timestamp.'.'.$extension;
              $file_d->move(public_path().'/storage/uploads', $name);
              $employee_model->file= $name;
              
            }
        }
        
        try{
            $save =  $employee_model->save();
            $result = ['msg'=>'successfully inserted'];
        }catch (Exception $e){
            $result = ['msg'=>$e->getMessage()];
        }
        return response()->json($result);
    }

}
