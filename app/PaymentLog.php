<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = ['TransactionType','TransID','TransTime','TransAmount','BusinessShortCode','BillRefNumber','InvoiceNumber','OrgAccountBalance','ThirdPartyTransID','MSISDN','FirstName','MiddleName','LastName'];
}
