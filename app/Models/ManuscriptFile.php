<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManuscriptFile extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [

        'manuscript_id',

        'manuscript_version_id',

        'file_id',

        'file_type',

        'original_name',

        'stored_name',

        'file_path',

        'file_size',

        'mime_type',

        'version_number',

        'uploaded_by',

        'status',

        'replacement_file_id',

    ];



    public function manuscript()
    {
        return $this->belongsTo(
            Manuscript::class
        );
    }



    public function uploader()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }


}