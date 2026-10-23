<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFacTory;

class Tache extends Model
{

  use HasFactory;
  public function user(): BelongsTo {
      return $this->belongsTo(User::class);
  }

  //Tache appartient à 1 utilisateurs
}

