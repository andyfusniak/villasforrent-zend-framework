#!/bin/bash
cat WebProducts.sql | r5-mirror
cat WebServices.sql | r5-mirror
cat Addresses.sql | r5-mirror
cat Payments.sql | r5-mirror
cat Invoices.sql | r5-mirror
cat InvoicePayments.sql | r5-mirror
cat InvoiceItems.sql | r5-mirror
cat Refunds.sql | r5-mirror
cat RefundInvoiceItems.sql | r5-mirror

