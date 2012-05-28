#!/bin/bash

CMD=villasforrentlocaldb

export VFR_PROJECT_HOME_DIR="`pwd`/../schema"
cd "$VFR_PROJECT_HOME_DIR"

#
# table structures
#

echo "Create DB Table CountryList"
cat CountryList.sql             | $CMD 
echo "Create DB Table Currencies"
cat Currencies.sql              | $CMD 
echo "Create DB Table IsoLanguageCode"
cat IsoLanguageCodes.sql        | $CMD 
echo "Create DB Table CurrencyConversions"
cat CurrencyConversions.sql     | $CMD

echo "Create DB Table Administrators"
cat Administrators.sql          | $CMD
echo "Create DB Table Advertisers"
cat Advertisers.sql             | $CMD

echo "Create DB Table ApiAccounts"
cat ApiAccounts.sql             | $CMD
echo "Create DB Table AdvertiserApiAccounts"
cat AdvertiserApiAccounts.sql   | $CMD

echo "Create DB Table Locations"
cat Locations.sql               | $CMD

echo "Create DB Table PropertyTypes"
cat PropertyTypes.sql | $CMD
echo "Create DB Table Facilities"
cat Facilities.sql  | $CMD
echo "Create DB Table Properties"
cat Properties.sql  | $CMD
echo "Create DB Table PropertiesFacilities"
cat PropertiesFacilities.sql | $CMD

echo "Create DB Table PropertiesContent"
cat PropertiesContent.sql | $CMD

echo "Create DB Table Calendars"
cat Calendars.sql   | $CMD
echo "Create DB Table Availability"
cat Availability.sql| $CMD

echo "Create DB Table DisapprovalStandardComments"
cat DisapprovalStandardComments.sql | $CMD
echo "Create DB Table Enquiries"
cat Enquiries.sql   | $CMD

echo "Create DB Table Members"
cat Members.sql | $CMD
echo "Create DB Table MessageThreads"
cat MessageThreads.sql | $CMD
echo "Create DB Table Messages"
cat Messages.sql | $CMD

echo "Create DB Table TaxCodes"
cat TaxCodes.sql | $CMD
echo "Create DB Table Orders"
cat Orders.sql | $CMD
echo "Create DB Table Services"
cat Services.sql | $CMD
echo "Create DB Table OrderServices"
cat OrdersServices.sql | $CMD

echo "Create DB Table Payments"
cat Payments.sql | $CMD
echo "Create DB Table PhotoApproval"
cat PhotoApprovals.sql | $CMD
echo "Create DB Table Photos"
cat Photos.sql | $CMD
echo "Create DB Table PropertiesStatistics"
cat PropertiesStatistics.sql | $CMD
echo "Create DB Table Rates"
cat Rates.sql | $CMD

echo "Create DB Table MembersFavoriteProperties"
cat MembersFavoriteProperties.sql | $CMD

#
# default data
#
echo "Setting up default data"
cat setup_db.sql | $CMD
