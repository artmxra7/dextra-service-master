<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\JobDayPhoto;

class JobDayPhotoController extends Controller
{
  /**
   * Resize a photo
   *
   * @param  integer $width        Image's width
   * @param  integer $height       Image's height
   * @param  string  $rawfile      RAW photo file location include filename
   * @param  string  $resultfile   Result photo file location include filename
   * @return void
   */

  private function resizePhoto($width, $height, $rawfile, $resultfile)
  {
    // Resize image file by aspect ratio
    Image::make($rawfile)->resize($width, $height, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    })->save($resultfile);
  }

  /**
   * Get photo list
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $jobDayID = Input::get('jobDayID');
    $photos = [];

    if ($jobDayID) {
      $photos = JobDayPhoto::where('job_day_id', $jobDayID)->get();
    } else {
      $photos = JobDayPhoto::all();
    }

    return response()->json([
      'message' => 'Get photos successfully',
      'data' => $photos,
    ], 200);
  }

  /**
   * Get a job photo
   *
   * @return \Illuminate\Http\Response
   */
  public function show($id) {
    $photo = JobDayPhoto::find($id);
    
    $responseMessage = $photo ? 'Get photo successfully' : 'Get photo failed';
    $responseCode = $photo ? 200 : 404;

    return response()->json([
      'message' => $responseMessage,
      'data' => $photo,
    ], $responseCode);
  }

  /**
   * Create new photo
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $req = $request->all();

    if ($request->hasFile('photo')) {
      $file = $request->photo;
      $extension = $file->getClientOriginalExtension();
      $title = 'attendance_'.$req['index'].'_'.$request->job_day_id;
      $rawFile = $title.'.'.$extension;
      $thumbnail = $title.'_thumbnail.'.$extension;
      $path = './attachments/attendances';

      $file->move($path, $rawFile);
      $this->resizePhoto(300, null, $path.'/'.$rawFile, $path.'/'.$thumbnail);

      $req['photo'] = $rawFile;
    }

    $photo = JobDayPhoto::create($req);
    
    $responseMessage = $photo ? 'Create photo successfully' : 'Create photo failed';
    $responseCode = $photo ? 200 : 400;

    return response()->json([
      'message' => $responseMessage,
      'data' => $photo,
    ], $responseCode);
  }

  /**
   * Update a photo
   *
   * @param  int                       Photo ID
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update($id, Request $request) {
    $photo = JobDayPhoto::find($id);
    $isUpdated = FALSE;

    if ($photo) {
      $req = $request->all();

      if ($request->hasFile('photo')) {
        $file = $request->photo;
        $extension = $file->getClientOriginalExtension();
        $title = 'attendance_'.$req['index'].'_'.$photo->job_day_id;
        $rawFile = $title.'.'.$extension;
        $thumbnail = $title.'_thumbnail.'.$extension;
        $path = './attachments/attendances';
  
        $file->move($path, $rawFile);
        $this->resizePhoto(300, null, $path.'/'.$rawFile, $path.'/'.$thumbnail);
  
        $req['photo'] = $rawFile;
      }

      $isUpdated = $photo->update($req);
    } else {
      return response()->json([
        'message' => 'Photo doesn\'t found',
        'data' => NULL,
      ], 404);
    }
    
    $responseMessage = $isUpdated ? 'Update photo successfully' : 'Update photo failed';
    $responseCode = $isUpdated ? 200 : 400;

    return response()->json([
      'message' => $responseMessage,
      'data' => $photo,
    ], $responseCode);
  }
}
