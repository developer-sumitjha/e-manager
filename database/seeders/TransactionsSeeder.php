<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = Account::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if ($accounts->isEmpty() || !$adminUser) {
            $this->command->info('No accounts or admin user found. Skipping transactions seeding.');
            return;
        }

        $cashAccount = $accounts->where('sub_type', 'cash')->first();
        $bankAccount = $accounts->where('sub_type', 'bank')->first();
        $salesAccount = $accounts->where('name', 'Sales Revenue')->first();
        $expenseAccount = $accounts->where('name', 'Office Rent')->first();

        $transactions = [
            // Income transactions
            [
                'account_id' => $salesAccount->id,
                'type' => 'income',
                'amount' => 1500.00,
                'description' => 'Product sales revenue',
                'reference' => 'SALE-001',
                'transaction_date' => Carbon::now()->subDays(5),
                'account_type' => 'cash',
                'notes' => 'Daily sales collection',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $salesAccount->id,
                'type' => 'income',
                'amount' => 2200.50,
                'description' => 'Product sales revenue',
                'reference' => 'SALE-002',
                'transaction_date' => Carbon::now()->subDays(3),
                'account_type' => 'bank',
                'notes' => 'Bank transfer from customer',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $salesAccount->id,
                'type' => 'income',
                'amount' => 890.75,
                'description' => 'Product sales revenue',
                'reference' => 'SALE-003',
                'transaction_date' => Carbon::now()->subDays(1),
                'account_type' => 'cash',
                'notes' => 'Cash payment from customer',
                'created_by' => $adminUser->id,
            ],

            // Expense transactions
            [
                'account_id' => $expenseAccount->id,
                'type' => 'expense',
                'amount' => 25000.00,
                'description' => 'Monthly office rent',
                'reference' => 'RENT-001',
                'transaction_date' => Carbon::now()->subDays(10),
                'account_type' => 'bank',
                'notes' => 'Monthly rent payment',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $accounts->where('name', 'Utilities')->first()->id,
                'type' => 'expense',
                'amount' => 3500.00,
                'description' => 'Electricity and internet bill',
                'reference' => 'UTIL-001',
                'transaction_date' => Carbon::now()->subDays(8),
                'account_type' => 'bank',
                'notes' => 'Monthly utilities payment',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $accounts->where('name', 'Marketing Expenses')->first()->id,
                'type' => 'expense',
                'amount' => 8000.00,
                'description' => 'Social media advertising',
                'reference' => 'MKT-001',
                'transaction_date' => Carbon::now()->subDays(6),
                'account_type' => 'bank',
                'notes' => 'Facebook and Google ads',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $accounts->where('name', 'Delivery Expenses')->first()->id,
                'type' => 'expense',
                'amount' => 1200.00,
                'description' => 'Delivery boy salary',
                'reference' => 'DEL-001',
                'transaction_date' => Carbon::now()->subDays(4),
                'account_type' => 'cash',
                'notes' => 'Weekly delivery expenses',
                'created_by' => $adminUser->id,
            ],

            // Purchase transactions
            [
                'account_id' => $accounts->where('name', 'Inventory')->first()->id,
                'type' => 'purchase',
                'amount' => 15000.00,
                'description' => 'Product inventory purchase',
                'reference' => 'PUR-001',
                'transaction_date' => Carbon::now()->subDays(7),
                'account_type' => 'bank',
                'notes' => 'Stock replenishment',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $accounts->where('name', 'Equipment')->first()->id,
                'type' => 'purchase',
                'amount' => 8500.00,
                'description' => 'Office equipment purchase',
                'reference' => 'EQP-001',
                'transaction_date' => Carbon::now()->subDays(12),
                'account_type' => 'bank',
                'notes' => 'New computer and printer',
                'created_by' => $adminUser->id,
            ],

            // Recent transactions for current month
            [
                'account_id' => $salesAccount->id,
                'type' => 'income',
                'amount' => 3200.00,
                'description' => 'Product sales revenue',
                'reference' => 'SALE-004',
                'transaction_date' => Carbon::now()->subDays(2),
                'account_type' => 'cash',
                'notes' => 'Weekend sales collection',
                'created_by' => $adminUser->id,
            ],
            [
                'account_id' => $accounts->where('name', 'Delivery Expenses')->first()->id,
                'type' => 'expense',
                'amount' => 800.00,
                'description' => 'Fuel and maintenance',
                'reference' => 'DEL-002',
                'transaction_date' => Carbon::now()->subDays(1),
                'account_type' => 'cash',
                'notes' => 'Vehicle maintenance',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}







