<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /** Stream rows as a CSV download (UTF-8 with BOM so Excel reads it correctly). */
    protected function csvDownload(string $filename, array $columns, iterable $rows)
    {
        return response()->streamDownload(function () use ($columns, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $columns);
            foreach ($rows as $row) {
                fputcsv($out, array_map(function ($column) use ($row) {
                    $value = (string) $row->{$column};
                    // Neutralise spreadsheet formulas typed into public forms.
                    return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
                }, $columns));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
