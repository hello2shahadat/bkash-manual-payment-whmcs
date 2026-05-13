<?php

/*
Module Name: bKash Manual Payment
Description: Manual bKash payment verification gateway for WHMCS invoices.
Developer: LionStar Host
Support: support@lionstarhost.com
*/

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function bkash_manual_MetaData()
{
    return [
        'DisplayName' => 'bKash Manual Payment',
        'APIVersion' => '1.1',
        'DisableLocalCreditCardInput' => true,
    ];
}

function bkash_manual_config()
{
    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'bKash Manual Payment',
        ],

        'bkashNumber' => [
            'FriendlyName' => 'bKash Number',
            'Type' => 'text',
            'Size' => '30',
            'Default' => '01701026261',
            'Description' => 'Enter your bKash payment number',
        ],

        'accountType' => [
            'FriendlyName' => 'Payment Type',
            'Type' => 'dropdown',
            'Options' => [
                'Payment' => 'Payment',
                'Send Money' => 'Send Money',
            ],
            'Default' => 'Payment',
        ],

        'departmentId' => [
            'FriendlyName' => 'Support Department ID',
            'Type' => 'text',
            'Size' => '10',
            'Default' => '1',
            'Description' => 'Ticket department ID for payment verification',
        ],

        'businessName' => [
            'FriendlyName' => 'Business Name',
            'Type' => 'text',
            'Size' => '50',
            'Default' => 'LionStar Host',
        ],

        'verificationNote' => [
            'FriendlyName' => 'Verification Note',
            'Type' => 'text',
            'Size' => '100',
            'Default' => 'Your invoice will be marked as paid after manual verification.',
        ],
    ];
}

function bkash_manual_link($params)
{
    $invoiceId = $params['invoiceid'];
    $amount = $params['amount'];
    $currency = $params['currency'];

    $bkashNumber = $params['bkashNumber'];
    $accountType = $params['accountType'];
    $departmentId = !empty($params['departmentId']) ? $params['departmentId'] : 1;
    $businessName = !empty($params['businessName']) ? $params['businessName'] : 'Billing Team';
    $verificationNote = !empty($params['verificationNote'])
        ? $params['verificationNote']
        : 'Your invoice will be marked as paid after manual verification.';

    $safeInvoiceId = intval($invoiceId);
    $modalId = 'bkashModal_' . $safeInvoiceId;

    $ticketSubject = 'bKash Payment Verification - Invoice #' . $invoiceId;

    $ticketUrlBase = 'submitticket.php?step=2'
        . '&deptid=' . urlencode($departmentId)
        . '&subject=' . urlencode($ticketSubject);

    return '
    <div style="text-align:right;margin-top:8px;">
        <button type="button"
            onclick="document.getElementById(\'' . $modalId . '\').style.display=\'flex\'"
            class="btn btn-primary"
            style="background:#d12053;border-color:#d12053;color:#ffffff;padding:8px 16px;border-radius:6px;font-size:14px;font-weight:700;box-shadow:0 3px 8px rgba(209,32,83,0.25);">
            Pay with bKash
        </button>
    </div>

    <div id="' . $modalId . '" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100%;height:100%;background:rgba(15,15,15,0.60);align-items:center;justify-content:center;padding:15px;box-sizing:border-box;">

        <div style="background:#ffffff;width:100%;max-width:430px;border-radius:14px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.28);font-family:Arial,sans-serif;">

            <div style="background:#d12053;color:#ffffff;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h3 style="margin:0;color:#ffffff;font-size:20px;font-weight:800;">bKash Payment</h3>
                    <div style="font-size:12px;color:#ffe8ef;margin-top:2px;">' . htmlspecialchars($businessName) . '</div>
                </div>

                <button type="button"
                    onclick="document.getElementById(\'' . $modalId . '\').style.display=\'none\'"
                    style="background:none;border:none;color:#ffffff;font-size:27px;line-height:1;cursor:pointer;padding:0 4px;">
                    &times;
                </button>
            </div>

            <div style="padding:16px;color:#333333;font-size:14px;line-height:1.45;">

                <div style="background:#fff5f8;border:1px solid #f3c1d0;border-radius:10px;padding:12px;margin-bottom:12px;text-align:center;">
                    <div style="font-size:12px;color:#666666;margin-bottom:4px;">Send payment to</div>

                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;">
                        <span id="bkash_number_' . $safeInvoiceId . '" style="font-size:25px;font-weight:900;color:#d12053;letter-spacing:.4px;">' . htmlspecialchars($bkashNumber) . '</span>

                        <button type="button"
                            onclick="
                                navigator.clipboard.writeText(document.getElementById(\'bkash_number_' . $safeInvoiceId . '\').innerText);
                                this.innerText=\'Copied\';
                                var btn=this;
                                setTimeout(function(){btn.innerText=\'Copy\';},1500);
                            "
                            style="background:#ffffff;border:1px solid #d12053;color:#d12053;border-radius:5px;padding:4px 8px;font-size:12px;cursor:pointer;">
                            Copy
                        </button>
                    </div>

                    <div style="font-size:13px;margin-top:6px;">
                        Type: <strong>' . htmlspecialchars($accountType) . '</strong>
                    </div>
                </div>

                <div style="display:flex;gap:8px;margin-bottom:12px;">
                    <div style="flex:1;background:#f9f9f9;border:1px solid #eeeeee;border-radius:8px;padding:9px;text-align:center;">
                        <div style="font-size:11px;color:#777777;">Invoice</div>
                        <strong>#' . htmlspecialchars($invoiceId) . '</strong>
                    </div>

                    <div style="flex:1;background:#f9f9f9;border:1px solid #eeeeee;border-radius:8px;padding:9px;text-align:center;">
                        <div style="font-size:11px;color:#777777;">Amount</div>
                        <strong>' . htmlspecialchars($amount) . ' ' . htmlspecialchars($currency) . '</strong>
                    </div>
                </div>

                <div style="background:#f7f7f7;border-left:4px solid #d12053;padding:9px 10px;border-radius:7px;font-size:13px;margin-bottom:12px;">
                    Send the exact amount. Use invoice number <strong>#' . htmlspecialchars($invoiceId) . '</strong> as reference if possible.
                </div>

                <form onsubmit="
                    var trx = document.getElementById(\'bkash_trx_' . $safeInvoiceId . '\').value.trim();
                    var sender = document.getElementById(\'bkash_sender_' . $safeInvoiceId . '\').value.trim();
                    var paid = document.getElementById(\'bkash_paid_' . $safeInvoiceId . '\').value.trim();

                    if (!trx || trx.length < 6) {
                        alert(\'Please enter a valid bKash Transaction ID.\');
                        return false;
                    }

                    if (!sender || sender.length < 11) {
                        alert(\'Please enter a valid sender bKash number.\');
                        return false;
                    }

                    if (!paid || parseFloat(paid) <= 0) {
                        alert(\'Please enter the paid amount.\');
                        return false;
                    }

                    var msg = \'Hello,\\n\\nI have completed the bKash payment for my invoice.\\n\\n\'
                        + \'Invoice Number: #' . htmlspecialchars($invoiceId) . '\\n\'
                        + \'Invoice Amount: ' . htmlspecialchars($amount) . ' ' . htmlspecialchars($currency) . '\\n\'
                        + \'Paid Amount: \' + paid + \' ' . htmlspecialchars($currency) . '\\n\'
                        + \'bKash Transaction ID: \' + trx + \'\\n\'
                        + \'Sender bKash Number: \' + sender + \'\\n\\n\'
                        + \'Please verify my payment and mark the invoice as paid.\\n\\nThank you.\';

                    window.location.href = \'' . htmlspecialchars($ticketUrlBase) . '&message=\' + encodeURIComponent(msg);
                    return false;
                ">

                    <label style="font-weight:700;margin-bottom:4px;display:block;font-size:13px;">Transaction ID</label>
                    <input type="text" id="bkash_trx_' . $safeInvoiceId . '" placeholder="Example: B8D9XXXXXX"
                        style="width:100%;padding:9px 10px;border:1px solid #cccccc;border-radius:7px;margin-bottom:9px;font-size:14px;box-sizing:border-box;">

                    <label style="font-weight:700;margin-bottom:4px;display:block;font-size:13px;">Sender bKash Number</label>
                    <input type="text" id="bkash_sender_' . $safeInvoiceId . '" placeholder="01XXXXXXXXX"
                        style="width:100%;padding:9px 10px;border:1px solid #cccccc;border-radius:7px;margin-bottom:9px;font-size:14px;box-sizing:border-box;">

                    <label style="font-weight:700;margin-bottom:4px;display:block;font-size:13px;">Paid Amount</label>
                    <input type="text" id="bkash_paid_' . $safeInvoiceId . '" value="' . htmlspecialchars($amount) . '"
                        style="width:100%;padding:9px 10px;border:1px solid #cccccc;border-radius:7px;margin-bottom:13px;font-size:14px;box-sizing:border-box;">

                    <button type="submit"
                        style="width:100%;background:#d12053;border:0;color:#ffffff;padding:11px;border-radius:7px;font-size:15px;font-weight:800;cursor:pointer;">
                        Submit Payment Details
                    </button>

                </form>

                <p style="margin:10px 0 0;font-size:12px;color:#777777;text-align:center;">
                    ' . htmlspecialchars($verificationNote) . '
                </p>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            var modal = document.getElementById("' . $modalId . '");
            if (modal) {
                modal.style.display = "none";
            }
        }
    });

    var bkashModal = document.getElementById("' . $modalId . '");
    if (bkashModal) {
        bkashModal.addEventListener("click", function(e) {
            if (e.target === this) {
                this.style.display = "none";
            }
        });
    }
    </script>';
}
