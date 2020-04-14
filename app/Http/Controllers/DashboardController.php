<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Complaint\ComplaintView;
use DB;
use Carbon\Carbon;
use App\Model\SMS\SMSHistoryView;

class DashboardController extends Controller
{
    public function index()
    {
       
        $data = [
            'today_complaints' => ComplaintView::where('complaint_date', date('Y-m-d') )->count(),
            'unrectified_complaints' => ComplaintView::whereIn('status_id',[1,2,5])->count(),
            'Inprocess_complaints' => ComplaintView::where('status_id', 2)->count(),
            'rectified_complaints' => ComplaintView::where('status_id', 4)->count(),  
            'sms_details' => SMSHistoryView::limit(9)->orderBy('sms_date')->get(['complaint_no', 'mobile_no', 'sms_date'])       
        ];      

        return api([
            'data' => $data,
            'covid_complaint_chart' => $this->getIncomeExpenseData()
        ]);
    }

    protected function getIncomeExpenseData()
    {
        // 28 days
        $start =  Carbon::now();
        $end = $start->copy()->subDays(28);
        $labels = $this->getDateRange($start, $end);
       
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Complaint',
                    'data' => $this->getComplaintData($start, $end, $labels),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Rectify',
                    'data' => $this->getRectifyData($start, $end, $labels),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    protected function getDateRange($startDate, $endDate)
    {
        $dates = [];
        $start = $startDate->copy();
        $end = $endDate->copy();
        while ($end->lte($start)) {
            $dates[] = $end->copy()->format('M d');
            $end->addDay(3);
        }

        return $dates;
    }

    protected function getComplaintData($start, $end, $label)
    {
        
        $default = collect($label)->mapWithKeys(function($item) {
            return [$item => 0];
        });
       
        $cp = ComplaintView::whereBetween('complaint_date', [$end, $start])
            ->select('complaint_date')
            ->where('status_id',1)
            ->get();

        $data = $cp->groupBy('complaint_date');

        $keyed = $default->mapWithKeys(function($key, $item) use ($data) {
            // get
            $date = Carbon::parse($item);

            $dates = [
                $date->copy()->format('Y-m-d'),
                $date->copy()->addDay(1)->format('Y-m-d'),
                $date->copy()->addDay(2)->format('Y-m-d')
            ];

            $sum = 0;
            foreach($dates as $key) {
                $items = $data->get($key);
                if(!is_null($items)) {
                    foreach($items as $item) {
                        $sum += 1;
                    }
                }
            }

            return [$date->copy()->format('Y-m-d') => $sum];
        });

        return array_values($keyed->all());
    }

    protected function getRectifyData($start, $end, $label)
    {
      
        $default = collect($label)->mapWithKeys(function($item) {
            return [$item => 0];
        });

        $cp = ComplaintView::whereBetween('complaint_date', [$end, $start])
            ->select('complaint_date')
            ->where('status_id',4)
            ->get();

        $data = $cp->groupBy('complaint_date');

        $keyed = $default->mapWithKeys(function($key, $item) use ($data) {
            // get
            $date = Carbon::parse($item);

            $dates = [
                $date->copy()->format('Y-m-d'),
                $date->copy()->addDay(1)->format('Y-m-d'),
                $date->copy()->addDay(2)->format('Y-m-d')
            ];

            $sum = 0;
            foreach($dates as $key) {
                $items = $data->get($key);
                if(!is_null($items)) {
                    foreach($items as $item) {
                        $sum += 1;
                    }
                }
            }

            return [$date->copy()->format('Y-m-d') => $sum];
        });

        return array_values($keyed->all());
    }

}
