INSERT INTO `WebProducts` (`idWebProduct`, `productCode`, `name`, `unitPrice`, `repeats`, `description`, `added`, `updated`) VALUES
(1, 'DNRE-DCOM', 'Domain Renewal (.com)', 30.00, 'YEARLY', 'Yearly domain renewal for .com domain', '2012-07-12 00:00:00', '2012-07-12 00:00:00'),
(2, 'HOST-PREM', 'Premium Website Hosting', 15.00, 'MONTHLY', 'Single domain owner website hosting', '2012-07-12 00:00:00', '2012-07-12 00:00:00'),
(3, 'DNRE-DCUK', 'Domain Renewal (.co.uk)', 40.00, '2YEAR', 'Domain renewal for .co.uk domain name', '2012-07-12 00:00:00', '2012-07-12 00:00:00'),
(4, 'DNRG-DCOM', 'Domain Registration (.com)', 30.00, 'YEARLY', 'Initial domain registration', '2012-07-12 00:00:00', '2012-07-12 00:00:00');

INSERT INTO `WebServices` (`idWebService`, `description`, `added`, `updated`) VALUES
(1, 'Website alterations for something.com website include this and that', '2012-07-12 00:00:00', '2012-07-12 00:00:00');


INSERT INTO `Addresses` (`idAddress`, `name`, `line1`, `line2`, `line3`, `townCity`, `county`, `postcode`, `country`, `added`, `updated`) VALUES
(1, 'Andy Fusniak', 'Technology House', '16-18 Whiteladies Road', '', 'Bristol', 'Avon', 'BS8 2LG', '', '2012-07-12 00:00:00', '2012-07-12 00:00:00'),
(2, 'Mr James Smith', '24 Timbuck Two', NULL, NULL, 'Cambridge', 'Cambridgeshire', 'CB1 3EG', 'UK', '2012-07-12 00:00:00', '2012-07-12 00:00:00');


INSERT INTO `Invoices` (`idInvoice`, `invoiceDate`, `total`, `currency`, `idBillingAddress`, `itemLastAdded`, `paid`, `added`, `updated`) VALUES
(00000001, '2012-06-29', 0.00, 'GBP', 2, NULL, 0, '2012-07-12 00:00:00', '2012-07-12 00:00:00');


INSERT INTO `Payments` (`idPayment`, `dateReceived`, `amount`, `currency`, `method`, `notes`, `added`, `updated`) VALUES
(1, '2012-07-12', 125.00, 'GBP', 'SECPAY', 'from mr smith natwest', '2012-07-12 00:00:00', '2012-07-12 00:00:00');
