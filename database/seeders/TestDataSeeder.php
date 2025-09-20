{{-- database/seeders/TestDataSeeder.php --}}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Invoice;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample customers
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1 (555) 123-4567',
                'company_name' => 'Smith Enterprises',
                'address' => '123 Business St, New York, NY 10001'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'phone' => '+1 (555) 987-6543',
                'company_name' => 'TechCorp Solutions',
                'address' => '456 Innovation Ave, San Francisco, CA 94105'
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'mdavis@consulting.com',
                'phone' => '+1 (555) 246-8135',
                'company_name' => 'Davis Consulting Group',
                'address' => '789 Professional Blvd, Chicago, IL 60601'
            ],
            [
                'name' => 'Emily Wilson',
                'email' => 'emily.wilson@gmail.com',
                'phone' => '+1 (555) 369-2580',
                'company_name' => null,
                'address' => '321 Residential Dr, Austin, TX 78701'
            ],
            [
                'name' => 'Robert Brown',
                'email' => 'rbrown@manufacturing.com',
                'phone' => '+1 (555) 147-2583',
                'company_name' => 'Brown Manufacturing Co.',
                'address' => '654 Industrial Way, Detroit, MI 48201'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create sample invoices
        $customerIds = Customer::pluck('id')->toArray();
        
        $invoices = [
            [
                'customer_id' => $customerIds[0],
                'amount' => 2500.00,
                'status' => 'Paid',
                'due_date' => now()->subDays(15),
                'description' => 'Web development services for Q1 2025',
                'created_at' => now()->subDays(45),
            ],
            [
                'customer_id' => $customerIds[1],
                'amount' => 1750.50,
                'status' => 'Pending',
                'due_date' => now()->addDays(15),
                'description' => 'IT consulting and system optimization',
                'created_at' => now()->subDays(20),
            ],
            [
                'customer_id' => $customerIds[2],
                'amount' => 3200.00,
                'status' => 'Paid',
                'due_date' => now()->subDays(5),
                'description' => 'Business strategy consultation',
                'created_at' => now()->subDays(30),
            ],
            [
                'customer_id' => $customerIds[0],
                'amount' => 950.75,
                'status' => 'Pending',
                'due_date' => now()->subDays(3), // Overdue
                'description' => 'Website maintenance and updates',
                'created_at' => now()->subDays(25),
            ],
            [
                'customer_id' => $customerIds[3],
                'amount' => 1200.00,
                'status' => 'Pending',
                'due_date' => now()->addDays(30),
                'description' => 'Personal financial consulting',
                'created_at' => now()->subDays(5),
            ],
            [
                'customer_id' => $customerIds[4],
                'amount' => 4500.00,
                'status' => 'Paid',
                'due_date' => now()->subDays(10),
                'description' => 'Manufacturing process optimization',
                'created_at' => now()->subDays(35),
            ],
            [
                'customer_id' => $customerIds[1],
                'amount' => 875.25,
                'status' => 'Pending',
                'due_date' => now()->addDays(20),
                'description' => 'Network security assessment',
                'created_at' => now()->subDays(10),
            ],
            [
                'customer_id' => $customerIds[2],
                'amount' => 2100.00,
                'status' => 'Paid',
                'due_date' => now()->subDays(20),
                'description' => 'Market research and analysis',
                'created_at' => now()->subDays(50),
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::create($invoiceData);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Created ' . count($customers) . ' customers and ' . count($invoices) . ' invoices.');
    }
}