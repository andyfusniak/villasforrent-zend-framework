#!/bin/bash

CMD=villasforrentlocaldb

export VFR_PROJECT_HOME_DIR="`pwd`/../schema"
cd "$VFR_PROJECT_HOME_DIR"

#
# table structures
#
echo "Deleting DB Table MembersFavoriteProperties"
echo "DROP TABLE IF EXISTS MembersFavoriteProperties" | $CMD 

echo "Deleting DB Table RemoteAccess"
echo "DROP TABLE IF EXISTS RemoteAccess" | $CMD

echo "Deleting DB Table RemoteServices"
echo "DROP TABLE IF EXISTS RemoteServices" | $CMD

echo "Deleting DB Table Rates"
echo "DROP TABLE IF EXISTS Rates" | $CMD

echo "Deleting DB Table PropertiesStatistics"
echo "DROP TABLE IF EXISTS PropertiesStatistics" | $CMD 

echo "Deleting DB Table Photos"
echo "DROP TABLE IF EXISTS Photos" | $CMD

echo "Deleting DB Table PhotoApproval"
echo "DROP TABLE IF EXISTS PhotoApprovals" | $CMD 

echo "Deleting DB Table Payments"
echo "DROP TABLE IF EXISTS Payments" | $CMD

echo "Deleting DB Table OrderServices"
echo "DROP TABLE IF EXISTS OrdersServices" | $CMD

echo "Deleting DB Table Services"
echo "DROP TABLE IF EXISTS Services" | $CMD

echo "Deleting DB Table Orders"
echo "DROP TABLE IF EXISTS Orders" | $CMD

echo "Deleting DB Table TaxCodes"
echo "DROP TABLE IF EXISTS TaxCodes" | $CMD 

echo "Deleting DB Table Messages"
echo "DROP TABLE IF EXISTS Messages" | $CMD 

echo "Deleting DB Table MessageThreads"
echo "DROP TABLE IF EXISTS MessageThreads" | $CMD 

echo "Deleting DB Table Members"
echo "DROP TABLE IF EXISTS Members" | $CMD 

echo "Deleting DB Table Enquiries"
echo "DROP TABLE IF EXISTS Enquiries" | $CMD 

echo "Deleting DB Table DisapprovalStandardComments"
echo "DROP TABLE IF EXISTS DisapprovalStandardComments" | $CMD 

echo "Deleting DB Table FastLookups"
echo "DROP TABLE IF EXISTS FastLookups" | $CMD

echo "Deleting DB Table Availability"
echo "DROP TABLE IF EXISTS Availability" | $CMD 

echo "Deleting DB Table Calendars"
echo "DROP TABLE IF EXISTS Calendars" | $CMD

echo "Deleting DB Table PropertiesContent"
echo "DROP TABLE IF EXISTS PropertiesContent" | $CMD

echo "Deleting DB Table PropertiesContentFields"
echo "DROP TABLE IF EXISTS PropertiesContentFields" | $CMD 

echo "Deleting DB Table PropertiesFacilities"
echo "DROP TABLE IF EXISTS PropertiesFacilities" | $CMD

echo "Deleting DB Table Properties"
echo "DROP TABLE IF EXISTS Properties" | $CMD 

echo "Deleting DB Table Facilities"
echo "DROP TABLE IF EXISTS Facilities" | $CMD 

echo "Deleting DB Table PropertyTypes"
echo "DROP TABLE IF EXISTS PropertyTypes" | $CMD 

echo "Deleting DB Table DestinationsQuickCount"
echo "DROP TABLE IF EXISTS DestinationsQuickCount" | $CMD 

echo "Deleting DB Table Destinations"
echo "DROP TABLE IF EXISTS Destinations" | $CMD 

echo "Deleting DB Table RegionsQuickCount"
echo "DROP TABLE IF EXISTS RegionsQuickCount" | $CMD

echo "Deleting DB Table Regions"
echo "DROP TABLE IF EXISTS Regions" | $CMD 

echo "Deleting DB Table CountriesQuickCount"
echo "DROP TABLE IF EXISTS CountriesQuickCount" | $CMD 

echo "Deleting DB Table Countries"
echo "DROP TABLE IF EXISTS Countries" | $CMD

echo "Deleting DB Table Advertisers"
echo "DROP TABLE IF EXISTS Advertisers" | $CMD 

echo "Deleting DB Table Administrators"
echo "DROP TABLE IF EXISTS Administrators" | $CMD 

echo "Deleting DB Table CurrencyConversions"
echo "DROP TABLE IF EXISTS CurrencyConversions" | $CMD 

echo "Deleting DB Table IsoLanguageCode"
echo "DROP TABLE IF EXISTS IsoLanguageCodes" | $CMD 

echo "Deleting DB Table Currencies"
echo "DROP TABLE IF EXISTS Currencies" | $CMD 

echo "Deleting DB Table CountryList"
echo "DROP TABLE IF EXISTS CountryList" | $CMD

