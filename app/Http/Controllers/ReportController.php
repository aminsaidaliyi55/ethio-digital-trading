<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function getOrdersBasedOnRole(Request $request, $timeFrame)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = Orders::query()
            ->where('status', 'completed')
            ->with(['product.shop.owner', 'product.shop.kebele.woreda.zone.region']);

        // Time-based filters
        switch ($timeFrame) {
            case 'daily':
                $query->whereDate('created_at', now()->toDateString());
                break;
            case 'monthly':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'yearly':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                // optional fallback
                break;
        }

        // Role-based filters
        if ($user->hasRole('RegionalAdmin')) {
            $query->whereHas('product.shop.kebele.woreda.zone.region', function ($regionQuery) use ($user) {
                $regionQuery->where('id', $user->region_id);
            });
        } elseif ($user->hasRole('ZoneAdmin')) {
            $query->whereHas('product.shop.kebele.woreda.zone', function ($zoneQuery) use ($user) {
                $zoneQuery->where('id', $user->zone_id);
            });
        } elseif ($user->hasRole('WoredaAdmin')) {
            $query->whereHas('product.shop.kebele.woreda', function ($woredaQuery) use ($user) {
                $woredaQuery->where('id', $user->woreda_id);
            });
        } elseif ($user->hasRole('KebeleAdmin')) {
            $query->whereHas('product.shop.kebele', function ($kebeleQuery) use ($user) {
                $kebeleQuery->where('id', $user->kebele_id);
            });
        } elseif ($user->hasRole('Owners')) {
            $query->whereHas('product.shop.owner', function ($userQuery) use ($user) {
                $userQuery->where('id', $user->id);
            });
        }

        // Search filter
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%$search%")
                                 ->orWhere('id', $search);
                })
                ->orWhere('quantity', $search)
                ->orWhere('total_price', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('id', $search)
                ->orWhere('created_at', 'like', "%$search%")
                ->orWhere('updated_at', 'like', "%$search%")
                ->orWhereHas('product.shop', function ($shopQuery) use ($search) {
                    $shopQuery->where('id', $search)
                              ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                                  $ownerQuery->where('id', $search)
                                             ->orWhere('name', 'like', "%$search%");
                              });
                })
                ->orWhereHas('product.shop.kebele', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('id', 'like', "%$search%");
                })
                ->orWhereHas('product.shop.kebele.woreda', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('id', 'like', "%$search%");
                })
                ->orWhereHas('product.shop.kebele.woreda.zone', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('id', 'like', "%$search%");
                })
                ->orWhereHas('product.shop', function ($query) use ($search) {
                    $query->where('TIN', 'like', "%$search%")
                          ->orWhere('id', 'like', "%$search%");
                })
                ->orWhereHas('product.shop.kebele.woreda.zone.region', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('id', 'like', "%$search%");
                });
            });
        }

        return $query;
    }

    // Daily report view
    public function indexDaily(Request $request)
    {
        
        $user=Auth::user();
        $approvedOrders = $this->getOrdersBasedOnRole($request, 'daily')->paginate(25);
        $totalApprovedPrice = $approvedOrders->sum('total_price');

    if ($user->hasRole('Owners') && $user->is_approved == 0) {
        return redirect()->route('owner.payment')->with('error', 'You must submit payment and wait for approval before creating a shop.');
    }
        return view('reports.daily', compact('approvedOrders', 'totalApprovedPrice'));
    }

    // Monthly report view
    public function indexMonthly(Request $request)
    {
        $user=Auth::user();
        $approvedOrders = $this->getOrdersBasedOnRole($request, 'monthly')->paginate(25);
        $totalApprovedPrice = $approvedOrders->sum('total_price');

    if ($user->hasRole('Owners') && $user->is_approved == 0) {
        return redirect()->route('owner.payment')->with('error', 'You must submit payment and wait for approval before creating a shop.');
    }
        return view('reports.monthly', compact('approvedOrders', 'totalApprovedPrice'));
    }

    // Yearly report view
    public function indexYearly(Request $request)
    {

                $user=Auth::user();

        $approvedOrders = $this->getOrdersBasedOnRole($request, 'yearly')->paginate(25);
        $totalApprovedPrice = $approvedOrders->sum('total_price');
 if ($user->hasRole('Owners') && $user->is_approved == 0) {
        return redirect()->route('owner.payment')->with('error', 'You must submit payment and wait for approval before creating a shop.');
    }
        return view('reports.yearly', compact('approvedOrders', 'totalApprovedPrice'));
    }



public function downloadDailyPdf(Request $request)
{
    $approvedOrders = $this->getOrdersBasedOnRole($request, 'daily')->get();
    $totalApprovedPrice = $approvedOrders->sum('total_price');

    $pdf = PDF::loadView('reports.daily_pdf', compact('approvedOrders', 'totalApprovedPrice'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('daily_report_' . now()->format('Y') . '.pdf');
}

    // Monthly PDF download
    public function downloadMonthlyPdf(Request $request)
    {
        $approvedOrders = $this->getOrdersBasedOnRole($request, 'monthly')->get();
        $totalApprovedPrice = $approvedOrders->sum('total_price');

        $pdf = PDF::loadView('reports.monthly_pdf', compact('approvedOrders', 'totalApprovedPrice'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('monthly_report_' . now()->format('Y-m') . '.pdf');
    }

    // Yearly PDF download
   public function downloadYearlyPdf(Request $request)
{
    $approvedOrders = $this->getOrdersBasedOnRole($request, 'yearly')->get();
    $totalApprovedPrice = $approvedOrders->sum('total_price');

    $pdf = PDF::loadView('reports.yearly_pdf', compact('approvedOrders', 'totalApprovedPrice'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('yearly_report_' . now()->format('Y') . '.pdf');
}



}
