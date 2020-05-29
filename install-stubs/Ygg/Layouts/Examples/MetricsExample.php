<?php

namespace App\Ygg\Layouts\Examples;

use Ygg\Screen\Layouts\Metric;

class MetricsExample extends Metric
{
    /**
     * @var string
     */
    protected $title = 'Metric Today';

    /**
     * @var string
     */
    protected $target = 'metrics';

    /**
     * @var array
     */
    protected $labels = [
        'Sales Today',
        'Visitors Today',
        'Total Earnings',
        'Pending Orders',
        'Total Revenue',
    ];
}
