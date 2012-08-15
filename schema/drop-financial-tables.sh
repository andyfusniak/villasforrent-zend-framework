#!/bin/bash
echo "DROP TABLE WebProducts" | r5-mirror
echo "DROP TABLE WebServices" | r5-mirror
echo "DROP TABLE Addresses" | r5-mirror
echo "DROP TABLE Payments" | r5-mirror
echo "DROP TABLE Invoices" | r5-mirror
echo "DROP TABLE InvoicePayments" | r5-mirror
echo "DROP TABLE InvoiceItems" | r5-mirror
echo "DROP TABLE Refunds" | r5-mirror
echo "DROP TABLE RefundInvoiceItems" | r5-mirror
