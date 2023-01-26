<?php

namespace App\Http\Controllers;

use Exception;
use DateTime;
use App\Models\File;
use App\Models\User;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Requests\DeleteFileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CurlNode;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = User::find(auth()->user()->id)->files()->orderBy('created_at', 'desc')->get();
        return view('dashboard', ['files'=>$files]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        try {
            $file = new File;
            $userDir = Str::beforeLast(auth()->user()->email, '@').'_'.auth()->user()->id;
            $file->name = time().'_'.$request->file->getClientOriginalName();
            $fileExt = Str::afterLast($file->name, '.');
            $file->file_path = $request->file('file')->storeAs('uploads/'.$userDir, $file->name, 'public');
            $file->user_id = auth()->user()->id;
            if(auth()->user()->address) {
                $response = Curlnode::post('/upload', [
                    'address' => auth()->user()->address,
                    'name' => $file->name,
                    'path' => $file->file_path,
                    'hash' => hash('sha3-256', file_get_contents($_FILES['file']['tmp_name'])),
                    'key' => $request->key
                ]);
                if(!session('connect_error')) {
                    $file->address = $response->address;
                    $file->save();
                    return response()->json([
                        'view' => view('ajax.file.store', ['file' => $file])->render(),
                        'status' => view('ajax.success', ['success' => 'File (' . $file->get_fullname() . ') has been uploaded.'])->render(),
                        'success' => true
                    ]);
                } else {
                    throw new Exception("Error: " . session('connect_error_message'));
                }
            } else {
                throw new Exception("Error: You have no blockchain account. Contact admin.");
            }
        }
        catch(Exception $e) {
            return response()->json([
                'status' => view('ajax.error', ['error' => $e->getMessage()])->render(),
                'error' => false
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFileRequest  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
    
    /**
     * Controller method for route to redirect to on failed validation
     *
     * @param  \App\Http\Requests\StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function error()
    {
        return response()->json([
            'status' => view('ajax.validation-error')->render()
        ]);
    }

    public function getcrc(File $file)
    {
        $creation_date = new DateTime($file->created_at);
        $creation_time = $creation_date->format('D, M j, Y \a\t g:i:s A');
        $verification_time = "";
        $verification_status = "";
        if($file->last_verified_at) {
            $verification_date = new DateTime($file->last_verified_at);
            $verification_time = $verification_date->format('D, M j, Y \a\t g:i:s A');
            $verification_status = $file->verification_status;
        }
        return response()->view('ajax.file.status', ['created'=>$creation_time, 'time'=>$verification_time, 'status'=>$verification_status]);
    }

    public function postcrc(File $file) 
    {
        try {
            if(auth()->user()->address) {
                if($file->address) {
                    $response = Curlnode::post('/crc', [
                        'address' => $file->address,
                        'hash' => hash('sha3-256', Storage::get($file->file_path))
                    ]);
                    if(!session('connect_error')) {
                        $file->last_verified_at = now();
                        $file->verification_status = $response->status;
                        $file->save();
                        if($response->status == "Passed") {
                            return response()->json([
                                'view' => view('ajax.file.verification-status', ['file' => $file])->render(),
                                'status' => view('ajax.success', ['success' => 'File (' . $file->get_fullname() . ') passed CRC.'])->render(),
                                'success' => true
                            ]);
                        } else if($response->status == "Failed") {
                            return response()->json([
                                'view' => view('ajax.file.verification-status', ['file' => $file])->render(),
                                'status' => view('ajax.error', ['error' => 'File (' . $file->get_fullname() . ') failed CRC.'])->render(),
                                'error' => true
                            ]);
                        }
                    } else {
                        throw new Exception("Error: " . session('connect_error_message'));
                    }
                } else {
                    throw new Exception("Error: File not in blockchain. Contact admin.");
                }
            } else {
                throw new Exception("Error: You have no blockchain account. Contact admin.");
            }
        }
        catch(Exception $e) {
            return response()->json([
                'status' => view('ajax.error', ['error' => $e->getMessage()])->render(),
                'error' => true
            ]);
        }
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     */
    public function download(File $file)
    {
        return Storage::download($file->file_path, $file->get_fullname());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DeleteFileRequest  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteFileRequest $request, File $file)
    {
        try {
            $userDir = Str::beforeLast(auth()->user()->email, '@').'_'.auth()->user()->id;
    
            if($file->address) {
                $response = Curlnode::post('/deletefile', [
                    'owner' => auth()->user()->address,
                    'address' => $file->address,
                    'key' => $request->key
                ]);
                if(!session('connect_error')) {
                    Storage::delete($file->file_path);
                    $fileName = $file->get_fullname();
                    $file->delete();
                    return response()->json([
                        'status' => view('ajax.success', ['success' => 'File (' . $fileName . ') has been deleted.'])->render(),
                        'success' => true
                    ]);
                } else {
                    throw new Exception("Error: " . session('connect_error_message'));
                }
            } else {
                throw new Exception("Error: File not in blockchain. Contact admin.");
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => view('ajax.error', ['error' => $e->getMessage()])->render(),
                'error' => true
            ]);
        }
    }
}
