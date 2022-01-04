<?php

namespace App\Imports;

use App\Models\upcoming_events;
use Maatwebsite\Excel\Concerns\ToModel;

class UpcomingEventsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new upcoming_events([
            //
        ]);
    }
}
