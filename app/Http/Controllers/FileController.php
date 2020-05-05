<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use League\Csv\Reader;

class FileController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        /** @var UploadedFile $file */
        $file = Arr::first($request->file());

        /** @var Reader $csv */
        $csv = Reader::createFromPath($file->getRealPath(), 'r')
            ->setDelimiter(',')
            ->setHeaderOffset(0);

        $records = collect($csv->getRecords());

        if ($request->get('save')) {
            // TODO: save new data here
            // File::firstOrCreate - based on code attribute?
        }

        // Average price
        $data['average_price'] = $records->average(function ($item) {
            return $item['average_price'] && is_numeric($item['average_price']) ? $item['average_price'] : 0;
        });

        // Total houses sold
        $data['total_houses_sold'] = $records->sum(function ($item) {
            return $item['houses_sold'] && is_numeric($item['houses_sold']) ? $item['houses_sold'] : 0;
        });

        // No of crimes in 2011
        $withCrimesIn2011 = $records->filter(function ($item) {
            return substr($item['date'], 0, 4) == '2011';
        });

        $data['no_of_crimes_in_2011'] = $withCrimesIn2011->sum(function ($item) {
            return $item['no_of_crimes'] && is_numeric($item['no_of_crimes']) ? $item['no_of_crimes'] : 0;
        });

        // Avg price per year in London area
        $londonArea = $records->filter(function ($item) {
            return strpos($item['area'], 'london');
        });

        $groupByYearInLondon = $londonArea->groupBy(function ($item) {
            return substr($item['date'], 0, 4);
        });

        foreach ($groupByYearInLondon as $key => $item) {
            $data['group_by_year'][$key] = $item->average(function ($item) {
                return $item['average_price'] && is_numeric($item['average_price']) ? $item['average_price'] : 0;
            });
        }

        // In real project I use https://github.com/flugger/laravel-responder for api responses
        return response($data);
    }
}
