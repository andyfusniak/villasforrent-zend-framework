#!/bin/bash

CMD=villasforrentlocaldb

export VFR_PROJECT_HOME_DIR="`pwd`/../schema"
cd "$VFR_PROJECT_HOME_DIR"

#
# table structures
#
echo "Deleting DB Table MembersFavoriteProperties"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS MembersFavoriteProperties" | $CMD 

echo "Deleting DB Table Rates"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Rates" | $CMD

echo "Deleting DB Table PropertiesStatistics"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS PropertiesStatistics" | $CMD 

echo "Deleting DB Table Photos"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Photos" | $CMD

echo "Deleting DB Table PhotoApproval"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS PhotoApprovals" | $CMD 

echo "Deleting DB Table Payments"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Payments" | $CMD

echo "Deleting DB Table OrderServices"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS OrdersServices" | $CMD

echo "Deleting DB Table Services"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Services" | $CMD

echo "Deleting DB Table Orders"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Orders" | $CMD

echo "Deleting DB Table TaxCodes"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS TaxCodes" | $CMD 

echo "Deleting DB Table Messages"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Messages" | $CMD 

echo "Deleting DB Table MessageThreads"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS MessageThreads" | $CMD 

echo "Deleting DB Table Members"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Members" | $CMD 

echo "Deleting DB Table Enquiries"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Enquiries" | $CMD 

echo "Deleting DB Table DisapprovalStandardComments"
echo "DROP TABLE IF EXISTS DisapprovalStandardComments" | $CMD 

echo "Deleting DB Table FastLookups"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS FastLookups" | $CMD

echo "Deleting DB Table Availability"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Availability" | $CMD 

echo "Deleting DB Table Calendars"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Calendars" | $CMD

echo "Deleting DB Table PropertiesContent"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS PropertiesContent" | $CMD

echo "Deleting DB Table PropertiesFacilities"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS PropertiesFacilities" | $CMD

echo "Deleting DB Table Facilities"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Facilities" | $CMD

echo "Deleting DB Table Properties"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Properties" | $CMD 

echo "Deleting DB Table PropertyTypes"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS PropertyTypes" | $CMD 

echo "Deleting DB Table Locations"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Locations" | $CMD

echo "Deleting DB Table Destinations"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Destinations" | $CMD 

echo "Deleting DB Table Regions"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Regions" | $CMD 

echo "Deleting DB Table Countries"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Countries" | $CMD

echo "Deleting DB Table AdvertiserApiAccounts"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS AdvertiserApiAccounts" | $CMD

echo "Deleting DB Table ApiAccounts"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS ApiAccounts" | $CMD

echo "Deleting DB Table Advertisers"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Advertisers" | $CMD 

echo "Deleting DB Table Administrators"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Administrators" | $CMD 

echo "Deleting DB Table CurrencyConversions"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS CurrencyConversions" | $CMD 

echo "Deleting DB Table IsoLanguageCode"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS IsoLanguageCodes" | $CMD 

echo "Deleting DB Table Currencies"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS Currencies" | $CMD 

echo "Deleting DB Table CountryList"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS CountryList" | $CMD

echo "Delete DB Table message (Zend_Queue 1 of 2)"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS message" | $CMD

echo "Delete DB Table message (Zend_Queue 2 of 2)"
echo "SET foreign_key_checks=0; DROP TABLE IF EXISTS queue" | $CMD

