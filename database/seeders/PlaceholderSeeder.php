<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Placeholder;

class PlaceholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $placeholders= [
            'First Name',
            'Last Name',
            'Company Name',
            'Title',
            'Email',
            'Phone Number',
            'Job Position',
            'Company Email',
            'Company Phone Number',

            'Addresse',
            'Company Addresse',
            'City',
            'ZIP Code',
            'Country',
            'State',

            'Date',
            'Day',
            'Month',
            'Year',
            'Duration',
            'Start Time',
            'End Time',

            'Interest Rate',
            'Amount',
            'Total Amount',
            'Payment',
            'Payment Method',
            'Pay Period',
            'Bank Account',
            'Account Number',
            'Bank Account Type',
            'Bank Name',
            'Credit Card Number',
            'Expiry Date',
            'Discount',

            'Product Code',
            'Product Name',
            'Product Description',
            'Quantity',
            'Price',
            'Order Date',
            'Delivery Date',

            'Logo',
            'Description',
            'Short Text',
            'Length Text',
            'Liste',
            'Authorized Scope of Work',
            'Terms and Conditions',
            'Deadline',
            'Signature',
         ];

         $identifications=[
            '[First Name]',
            '[Last Name]',
            '[Company Name]',
            '[Title]',
            '[Email]',
            '[Phone Number]',
            '[Job Position]',
            '[Company Email]',
            '[Company Phone Number]',

            '[Addresse]',
            '[Company Addresse]',
            '[City]',
            '[ZIP Code]',
            '[Country]',
            '[State]',

            '[Date]',
            '[Day]',
            '[Month]',
            '[Year]',
            '[Duration]',
            '[Start Time]',
            '[End Time]',

            '[Interest Rate]',
            '[Amount]',
            '[Total Amount]',
            '[Payment]',
            '[Payment Method]',
            '[Pay Period]',
            '[Bank Account]',
            '[Account Number]',
            '[Bank Account Type]',
            '[Bank Name]',
            '[Credit Card Number]',
            '[Expiry Date]',
            '[Discount]',

            '[Product Code]',
            '[Product Name]',
            '[Product Description]',
            '[Quantity]',
            '[Price]',
            '[Order Date]',
            '[Delivery Date]',

            '[Logo]',
            '[Description]',
            '[Short Text]',
            '[Length Text]',
            '[Liste]',
            '[Authorized Scope of Work]',
            '[Terms and Conditions]',
            '[Deadline]',
            '[Signature]',
         ];
         $placeholdersWithInfo = array_map(null, $placeholders, $identifications);

         foreach ($placeholdersWithInfo as $placeholderInfo) {
             $placeholder = new Placeholder([
                 'name' => $placeholderInfo[0],
                 'identification' => $placeholderInfo[1],
             ]);
             $placeholder->save();
         }
    }
}
