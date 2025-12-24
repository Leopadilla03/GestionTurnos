<?php

namespace App\Services;

class ChartService
{
    /**
     * Gráfica de barras: Turnos por Departamento
     */
    public static function turnosPorDepartamento($labels, $data, $titulo = 'Turnos por Departamento')
    {
        return [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => $titulo,
                    'data' => $data,
                    'backgroundColor' => [
                        '#004B93',
                        '#2ca02c',
                        '#d62728',
                        '#ff7f0e',
                    ],
                    'borderWidth' => 1
                ]]
            ],
            'options' => [
                'responsive' => true,
                'legend' => [
                    'display' => true,
                ],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true,
                            'min' => 0,
                            'max' => 10,
                            'stepSize' => 1,
                        ]
                    ]]
                ]
            ]
        ];
    }

    /**
     * Convierte config Chart.js a imagen (QuickChart)
     */
    public static function toQuickChart($chartConfig, $width = 800, $height = 320)
    {
        return 'https://quickchart.io/chart'
            . '?version=2.9.4'
            . '&w=' . $width
            . '&h=' . $height
            . '&backgroundColor=white'
            . '&c=' . urlencode(json_encode($chartConfig));
    }
}
