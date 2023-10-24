<?php

namespace App\Repositories;

use App\Models\Invoice;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InvoiceRepository
{

    /**
     * @param string $stripeId 
     * @return Invoice|null 
     * @throws InvalidArgumentException 
     * @throws ModelNotFoundException 
     */
    public function findByStripeId(string $stripeId): Invoice
    {
        return Invoice::query()->where(Invoice::FIELD_STRIPE_ID, $stripeId)->firstOrFail();
    }


    /**
     * @param array $data 
     * @return Invoice 
     * @throws Exception 
     */
    public function createInvoice(array $data): Invoice
    {
        try { 
            DB::beginTransaction();
            $invoice = new Invoice();
            $invoice->fill($data);
            $invoice->save();
            DB::commit();

            return $invoice;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }

    /**
     * 
     * @param Invoice $invoice 
     * @param array $data 
     * @return bool 
     * @throws Exception 
     */
    public function updateInvoice(Invoice $invoice, array $data): bool
    {
        try {
            DB::beginTransaction();
            $invoice->fill($data);
            return $invoice->save();
            DB::commit();

            return $invoice;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }             
    }
}
