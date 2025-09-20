{{-- resources/views/invoices/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .company-name { 
            font-size: 24px; 
            font-weight: bold; 
            color: #667eea;
            margin-bottom: 5px;
        }
        .invoice-title { 
            font-size: 20px; 
            color: #666;
        }
        .invoice-details { 
            display: table; 
            width: 100%; 
            margin-bottom: 30px;
        }
        .invoice-details .left, .invoice-details .right { 
            display: table-cell; 
            vertical-align: top; 
            width: 50%;
        }
        .invoice-details .right { 
            text-align: right;
        }
        .section-title { 
            font-weight: bold; 
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .invoice-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
        }
        .invoice-table th, .invoice-table td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left;
        }
        .invoice-table th { 
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row { 
            background-color: #667eea !important; 
            color: white;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid { background-color: #28a745; color: white; }
        .status-pending { background-color: #ffc107; color: #212529; }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Invoice Management System</div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="invoice-details">
        <div class="left">
            <div class="section-title">BILL TO:</div>
            <div>
                <strong>{{ $invoice->customer->name }}</strong><br>
                @if($invoice->customer->company_name)
                    {{ $invoice->customer->company_name }}<br>
                @endif
                {{ $invoice->customer->email }}<br>
                @if($invoice->customer->phone)
                    {{ $invoice->customer->phone }}<br>
                @endif
                @if($invoice->customer->address)
                    {{ $invoice->customer->address }}
                @endif
            </div>
        </div>
        <div class="right">
            <div class="section-title">INVOICE DETAILS:</div>
            <div>
                <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                <strong>Status:</strong> 
                <span class="status-badge status-{{ strtolower($invoice->status) }}">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width: 20%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($invoice->description)
                        {{ $invoice->description }}
                    @else
                        Professional Services
                    @endif
                </td>
                <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>TOTAL</strong></td>
                <td style="text-align: right;"><strong>${{ number_format($invoice->amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($invoice->description)
        <div style="margin-top: 30px;">
            <div class="section-title">NOTES:</div>
            <div>{{ $invoice->description }}</div>
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This invoice was generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>