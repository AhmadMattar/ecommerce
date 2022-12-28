<?php

namespace App\Http\Livewire\Backend\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Chart extends Component
{
    public $MonthLabels = [];
    public $MonthValues = [];

    public function mount()
    {
        $orders = Order::whereOrderStatus(Order::FINISHED)->select(DB::raw('SUM(total) as revenue'), DB::raw('Month(created_at) as month'))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('revenue', 'month');

        foreach ($orders->keys() as $month_number) {
            $this->MonthLabels[] = date('F', mktime(0, 0, 0, $month_number, 1));
        }

        $this->MonthValues = $orders->values()->toArray();
    }
    public function render()
    {
        return view('livewire.backend.dashboard.chart');
    }
}
