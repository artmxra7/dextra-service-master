<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Repositories\CommissionRepository;
use App\Http\Controllers\AppBaseController as Controller;
use Carbon\Carbon;

use App\Models\Commission;
use App\Models\Setting;
use App\Models\Order;
use App\Models\Job;

class CommissionController extends Controller
{
    /** @var  CommissionRepository */
    private $commissionRepository;

    public function __construct(CommissionRepository $commissionRepo)
    {
        $this->commissionRepository = $commissionRepo;
    }

    public function index()
    {
        $commissions = Commission::where('user_id', auth()->id())
                        ->latest()
                        ->get();
        return $this->sendResponse($commissions, "success");
    }

    public function show($id)
    {

        $commission = $this->commissionRepository->getCommission([
            'id' => $id
        ]);

        if (empty($commission)) {
            return $this->sendError('Commission not found.', 404);
        }

        return $this->sendResponse($commission, "success");
    }

    public function store(Request $request)
    {
        $content = NULL;

        switch ($request->type) {
            case 'sparepart':
                $content = Order::find($request->id);
                break;
            case 'job':
                $content = Job::with('job_days', 'quotation')->find($request->id);
                break;
            default:
                break;
        }

        if ($content) {
            if ($request->type == 'sparepart') {
                $percent = Setting::where('key', 'SALES_COMMISION')->first();
                $amount = $content->total_price * $percent->value / 100;

                $commission = [
                    'user_id'       => $request->user_id,
                    'type'          => $request->type,
                    'order_id'      => $request->id,
                    'description'   => "You get a {$percent->value}% commissions from {$amount}.",
                    'amount'        => $amount,
                ];

                Commission::create($commission);
            } else {
                $percent = Setting::where('key', 'MECHANIC_COMMISION')->first();
                $price = $content->quotation->price;
                $amount = $price * $percent->value / 100;
                $totalWorkHours = 0;
                $commissions = [];

                // Sum total work hours by all mechanic
                foreach($content->job_days as $day) {
                    $date = new \DateTime($day->working_hours);
                    $totalWorkHours += (int)$date->format('H');
                }

                // Generate commissions data
                foreach($content->job_days as $day) {
                    $date = new \DateTime($day->working_hours);
                    $hours = (int)$date->format('H');
                    if ($totalWorkHours > 0) {
                        $result = $hours / $totalWorkHours * $amount;
                    }else {
                        $result = 0;
                    }

                    $commissions[] = [
                        'user_id'       => $day->user_mechanic_id,
                        'type'          => $request->type,
                        'job_id'        => $request->id,
                        'description'   => "You get a {$result} from {$amount}.",
                        'amount'        => $result,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ];
                }

                Commission::insert($commissions);
            }
        }

        if (empty($content)) {
            return $this->sendError('Commision add failed.', 500);
        }

        return $this->sendResponse(NULL, "success");
    }
}
