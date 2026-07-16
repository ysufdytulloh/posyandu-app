<?php

namespace App\Observers;

use App\Models\TimbangBalita;
use App\Services\ZScoreCalculator;

class TimbangBalitaObserver
{
    public function created(TimbangBalita $timbangBalita): void
    {
        (new ZScoreCalculator())->calculate($timbangBalita);
    }

    public function updated(TimbangBalita $timbangBalita): void
    {
        (new ZScoreCalculator())->calculate($timbangBalita);
    }
}
