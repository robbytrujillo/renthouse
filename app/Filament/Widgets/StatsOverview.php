<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    private function getPercentage(int $from,int $to) {
        // Jika from = 0
        if ($from === 0) {
            if ($to === 0) {
                return 0; // Tidak ada perubahan
            }
            return 100; // Dari nol ke ada nilai, anggap 100% naik
        }
        return $to - $from / ($to + $from / 2) * 100;
        // return $to - (($from / ($to + ($from / 2))) * 100);
    }
    protected function getStats(): array
    {
        $newListing = Listing::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $transactions = Transaction::whereStatus('approved')->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        $prevTransactions = Transaction::whereStatus('approved')->whereMonth('created_at',Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->subMonth()->year);
        $transactionPercentage = $this->getPercentage($prevTransactions->count(), $transactions->count());
        $revenuePercentage = $this->getPercentage($prevTransactions->sum('total_price'), $transactions->sum('total_price'));

        return [
            Stat::make('New listing of the month', $newListing),
            Stat::make('Transaction of the month', $transactions->count())
                ->description($transactionPercentage > 0 ? "{$transactionPercentage}% increased" : "{$transactionPercentage}% decreased")
                ->descriptionIcon($transactionPercentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transactionPercentage > 0 ? 'success' : 'danger'),
            Stat::make('Revenue of the month', Number::currency($transactions->sum('total_price'), 'USD'))
                ->description($revenuePercentage > 0 ? "{$revenuePercentage}% increased" : "{$revenuePercentage}% decreased")
                ->descriptionIcon($revenuePercentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenuePercentage > 0 ? 'success' : 'danger'),
            // Stat::make('Average time on page', '3:12'),
        ];
    }
}