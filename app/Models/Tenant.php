<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }
    // public function submissionDocuments()
    // {
    //     return $this->belongsToMany(SubmissionDocument::class, 'submission_document_user')
    //                 ->withPivot('user_id', 'file_path')
    //                 ->withTimestamps();
    // }
}
