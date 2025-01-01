<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    private function getPercentage(int $from,int $to) {
        return $to - $from / ($to + $from / 2) * 100;
    }
    protected function getStats(): array
    {
        $newListing = Listing::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $transactions = Transaction::whereStatus('approved')->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        $prevTransactions = Transaction::whereStatus('approved')->whereMonth('created_at',Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->subMonth()->year);
        $transactionPercentage = $this->getPercentage($prevTransactions->count(), $transactions->count());

        return [
            Stat::make('New listing of the month', $newListing),
            Stat::make('Transaction of the month', $transactions->count())
                ->description($transactionPercentage > 0 ? "{$transactionPercentage}% increased" : "{$transactionPercentage}% decreased"),
            // Stat::make('Average time on page', '3:12'),
        ];
    }
}
