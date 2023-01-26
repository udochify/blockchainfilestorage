<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    /**
     * The URI that users should be redirected to if validation fails.
     * 
     * @var string
     */
    protected $redirectRoute = 'files.error';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check()) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:txt,pdf,csv,xlx,xls,xlsx,doc,docx,html,htm,css,js,jpg,jpeg,png,gif,mp4,avi,3gp,webm,wav,ogg,mp3|max:5120'
            // 'file' => 'required|mimes:csv,txt,xlx,xls,pdf,jpg,png,pem,xlsx,docx,ppt,pptx,zip,rar,mp4,avi,3gp,webm,wav,ogg,mp3,html,css,js,doc,apk,dmg,iso|max:1024'
        ];
    }
}
