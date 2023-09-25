<?php

namespace App\Charts;

use App\Models\Surat;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SuratChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $surats = Surat::all();
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $data = [];

        foreach ($months as $month) {
            $suratsInMonth = $surats->filter(function ($surat) use ($month) {
                $tanggalSurat = $surat->tanggal_surat;
                $formattedMonth = date('F', strtotime($tanggalSurat));

                return $formattedMonth === $month;
            });

            $data[] = $suratsInMonth->count();
        }

        return $this->chart->barChart()
            ->setTitle('Monthly Data Surat')
            ->setSubtitle(date('Y'))
            ->setWidth(500)
            ->setHeight(333)
            ->addData('Surat', $data)
            ->setXAxis($months);
    }
}
