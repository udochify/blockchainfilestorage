<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'name',
        'file_path',
        'user_id',
        'last_verified_at',
        'verification_status'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function get_name($len = 100)
    {
        if ( strlen($sub = (substr($this->name, strpos($this->name, '_')+1))) <= $len)
            return $sub;
        return substr_replace($sub, '...', $len);
    }

    public function get_fullname() 
    {
        return substr($this->name, strpos($this->name, '_')+1);
    }

    public function getFileSize() 
    {
        $size = Storage::size($this->file_path);

        switch (true) 
        {
            case ($size/1024 < 1):
                return $size.'B';
            case ($size/pow(1024, 2) < 1):
                return round($size/1024, 2).'KB';
            case ($size/pow(1024, 3) < 1):
                return round($size/pow(1024, 2), 2).'MB';
            case ($size/pow(1024, 4) < 1):
                return round($size/pow(1024, 3), 2).'GB';
            default:
                return round($size/pow(1024, 4), 2).'TB';
        }
    }
}
