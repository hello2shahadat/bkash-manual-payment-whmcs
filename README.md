# bKash Manual Payment for WHMCS

A free manual bKash payment verification gateway module for WHMCS.

This module allows Bangladeshi hosting providers and online businesses to accept manual bKash payments from clients without official bKash API access.

It adds a clean **Pay with bKash** button on unpaid WHMCS invoices. Clients can open a professional popup, view payment instructions, copy the bKash number, enter Transaction ID, sender bKash number, and paid amount, then submit the information through a pre-filled WHMCS support ticket.

## Important Notice

This is a manual payment verification module.

It is not an official bKash API gateway and it does not automatically verify payments through the bKash API.

After the client submits payment details, the WHMCS admin must manually verify the payment from their bKash account and mark the invoice as paid.

## Features

- Manual bKash payment method for WHMCS invoices
- Clean **Pay with bKash** button
- Professional popup payment window
- Copy bKash number button
- Transaction ID collection
- Sender bKash number collection
- Paid amount collection
- Pre-filled WHMCS support ticket submission
- Configurable bKash number
- Configurable payment type
- Configurable business name
- Configurable support department ID
- No official bKash API required

## Requirements

- WHMCS 8.x recommended
- PHP 7.4 or later recommended
- Active WHMCS support ticket department
- bKash number for receiving manual payments

## Installation

1. Download the latest release ZIP file.
2. Upload the contents of the ZIP file to your WHMCS root directory.
3. Make sure the files are placed like this:

```text
/modules/gateways/bkash_manual.php
/modules/gateways/bkash_manual/whmcs.json
/modules/gateways/bkash_manual/logo.png
