<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets
            [
                'name' => 'Cash Account',
                'account_number' => 'ACC-001',
                'type' => 'asset',
                'sub_type' => 'cash',
                'opening_balance' => 50000.00,
                'current_balance' => 50000.00,
                'description' => 'Main cash account for daily operations',
                'is_active' => true,
            ],
            [
                'name' => 'Bank Account - NMB',
                'account_number' => 'ACC-002',
                'type' => 'asset',
                'sub_type' => 'bank',
                'opening_balance' => 100000.00,
                'current_balance' => 100000.00,
                'description' => 'NMB Bank checking account',
                'is_active' => true,
            ],
            [
                'name' => 'Accounts Receivable',
                'account_number' => 'ACC-003',
                'type' => 'asset',
                'sub_type' => 'receivable',
                'opening_balance' => 25000.00,
                'current_balance' => 25000.00,
                'description' => 'Amounts owed by customers',
                'is_active' => true,
            ],
            [
                'name' => 'Inventory',
                'account_number' => 'ACC-004',
                'type' => 'asset',
                'sub_type' => 'inventory',
                'opening_balance' => 75000.00,
                'current_balance' => 75000.00,
                'description' => 'Product inventory value',
                'is_active' => true,
            ],
            [
                'name' => 'Equipment',
                'account_number' => 'ACC-005',
                'type' => 'asset',
                'sub_type' => 'equipment',
                'opening_balance' => 50000.00,
                'current_balance' => 50000.00,
                'description' => 'Office equipment and furniture',
                'is_active' => true,
            ],

            // Liabilities
            [
                'name' => 'Accounts Payable',
                'account_number' => 'ACC-006',
                'type' => 'liability',
                'sub_type' => 'payable',
                'opening_balance' => 15000.00,
                'current_balance' => 15000.00,
                'description' => 'Amounts owed to suppliers',
                'is_active' => true,
            ],
            [
                'name' => 'Bank Loan',
                'account_number' => 'ACC-007',
                'type' => 'liability',
                'sub_type' => 'payable',
                'opening_balance' => 200000.00,
                'current_balance' => 200000.00,
                'description' => 'Business loan from bank',
                'is_active' => true,
            ],

            // Equity
            [
                'name' => 'Owner\'s Equity',
                'account_number' => 'ACC-008',
                'type' => 'equity',
                'sub_type' => 'other',
                'opening_balance' => 300000.00,
                'current_balance' => 300000.00,
                'description' => 'Owner\'s investment in the business',
                'is_active' => true,
            ],

            // Income
            [
                'name' => 'Sales Revenue',
                'account_number' => 'ACC-009',
                'type' => 'income',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Revenue from product sales',
                'is_active' => true,
            ],
            [
                'name' => 'Service Revenue',
                'account_number' => 'ACC-010',
                'type' => 'income',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Revenue from services',
                'is_active' => true,
            ],

            // Expenses
            [
                'name' => 'Office Rent',
                'account_number' => 'ACC-011',
                'type' => 'expense',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Monthly office rent expense',
                'is_active' => true,
            ],
            [
                'name' => 'Utilities',
                'account_number' => 'ACC-012',
                'type' => 'expense',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Electricity, water, internet bills',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Expenses',
                'account_number' => 'ACC-013',
                'type' => 'expense',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Advertising and marketing costs',
                'is_active' => true,
            ],
            [
                'name' => 'Delivery Expenses',
                'account_number' => 'ACC-014',
                'type' => 'expense',
                'sub_type' => 'other',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'description' => 'Delivery and shipping costs',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}







