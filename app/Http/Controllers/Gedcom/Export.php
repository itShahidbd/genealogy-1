<?php

namespace App\Http\Controllers\Gedcom;

use App\Http\Controllers\Controller;
use App\Jobs\ExportGedCom;
use App\Models\Family;
use App\Models\Person;
use FamilyTree365\LaravelGedcom\Utils\GedcomGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Export extends Controller
{
    public function __invoke(Request $request)
    {

        $ts = microtime(true);
        $file = env('APP_NAME').date('_Ymd_').$ts.'.ged';
        $file = str_replace(' ', '', $file);
        $file = str_replace("'", '', $file);
        $_name = uniqid().'.ged';

        ExportGedCom::dispatch($file, $request->user());

        Log::info('Read gedfile from '.\Storage::disk('public')->path($file));
        // var_dump(\Storage::disk("public")->path($file), "controller");
        return json_encode([
            'file' => file_get_contents('/home/genealogia/domains/api.genealogia.co.uk/genealogy/storage/tenant_'
		     . tenant('id') . '/app/' . $file),
            'name' => $file,
        ]);
    }
}
