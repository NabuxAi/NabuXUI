<?php

namespace App\Models;

use Database\Factories\EmailFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['subject', 'sender_name', 'sender_email', 'body', 'folder', 'is_read', 'received_at'])]
class Email extends Model
{
    /** @use HasFactory<EmailFactory> */
    use HasFactory;

    public const FOLDERS = ['inbox', 'archive'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'received_at' => 'datetime',
        ];
    }
}
