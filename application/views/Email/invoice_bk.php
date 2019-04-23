<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

        <title>AsiaTech Invoice</title>

        <style>
            body { font: 14px/1.4 Georgia, serif; }
            #page-wrap { width: 800px; margin: 0 auto; }

            span { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
            table { border-collapse: collapse; }
            table td, table th { border: 1px solid black; padding: 5px; }

            #header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }

            #address { width: 250px; height: 150px; float: left; }
            #customer { overflow: hidden; }

            #logo { text-align: right; float: right; position: relative; margin-top: 25px; border: 1px solid #fff; max-width: 540px; max-height: 100px; overflow: hidden; }
            #logo:hover, #logo.edit { border: 1px solid #000; margin-top: 0px; max-height: 125px; }
            #logoctr { display: none; }
            #logo:hover #logoctr, #logo.edit #logoctr { display: block; text-align: right; line-height: 25px; background: #eee; padding: 0 5px; }
            #logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
            #logohelp input { margin-bottom: 5px; }
            .edit #logohelp { display: block; }
            .edit #save-logo, .edit #cancel-logo { display: inline; }
            .edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
            #customer-title { font-size: 20px; font-weight: bold; float: left; }

            #meta { margin-top: 1px; width: 300px; float: right; }
            #meta td { text-align: right;  }
            #meta td.meta-head { text-align: left; background: #eee; }
            #meta td textarea { width: 100%; height: 20px; text-align: right; }

            #items { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
            #items th { background: #eee; }
            #items textarea { width: 80px; height: 50px; }
            #items tr.item-row td { vertical-align: top; }
            #items td.description { width: 300px; }
            #items td.item-name { width: 175px; }
            #items td.description textarea, #items td.item-name textarea { width: 100%; }
            #items td.total-line { border-right: 0; text-align: right; }
            #items td.total-value textarea { height: 20px; background: none; }
            #items td.balance { background: #eee; }
            #items td.blank { border: 0; }

            #terms { text-align: center; margin: 20px 0 0 0; }
            #terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
            #terms textarea { width: 100%; text-align: center;}

            textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }

            .delete-wpr { position: relative; }
            .delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }


        </style>
    </head>

    <body>

        <div id="page-wrap">

            <div id="header">RECEIPT</div>

            <div id="identity">

                <center> <h2> ASIA TECH CENTER PVT LTD.</h2><br/>
                    Address:- Ground Floor, "Sunshine Plaza", Station Road, Ambedkar Chowk,<br/>
                    Above Ratna Hotel, Pimpri, Pune-411018</center><hr>
            </div>

            <div style="clear:both"></div>
            <br/>
            <div id="customer">

                <span>Name of Candidate</span><br/>
                <span><?= $candidateFeesPaidDetails['first_name'].' '.$candidateFeesPaidDetails['last_name'] ?></span>

                <table id="meta">
                    <tr>
                        <td style="text-align:left; background:#eee;">Receipt No.</td>
                        <td><span><?= $candidateFeesPaidDetails['payment_receipt_no']?></span></td>
                    </tr>
                    <tr>

                        <td class="meta-head">Date</td>
                        <td><span id="date"><?= $candidateFeesPaidDetails['payment_date']?></span></td>
                    </tr>

                </table>

            </div>

            <table id="items">

                <tr>
                    <th>Course</th>
<!--                    <th>Description</th>-->
                    <th>Total Training Cost</th>
                    <th>Installment No.</th>
                    <th>Amount Received</th>
                </tr>

                <tr class="item-row">
                    <td class="item-name"><div class="delete-wpr"><span><?= $candidateFeesPaidDetails['course_name']?></span></div></td>
<!--                    <td class="description"><span><?php ?></span></td>-->
                    <td style="text-align:right;" ><span class="cost"><?= $candidateFeesPaidDetails['final_fees']?></span></td>
                    <td style="text-align:right;" ><span class="qty"><?= $candidateFeesPaidDetails['payment_sequence']?></span></td>
                    <td style="text-align:right;"><span class="price"><?= $candidateFeesPaidDetails['fees_paid']?></span></td>
                </tr>



                <tr>
                    <td colspan="2"> </td>
                    <td class="total-line-balance" style="font: 14px Georgia, Serif;">Total Amount Received</td>
                    <td class="total-value"><div id="subtotal" style="text-align:right; font: 14px Georgia, Serif;" ><?= $candidateFeesPaidDetails['totalPaidFees']?></div></td>
                </tr>
                <tr>

                    <td colspan="2"> </td>
                    <td class="total-line-balance" style="font-weight:bold; font: 14px Georgia, Serif;"><b>Total Amount Outstanding</b></td>
                    <td class="total-value"><div id="total" style="text-align:right;font-weight:bold; font: 14px Georgia, Serif;"><b><?= $candidateFeesPaidDetails['remainginFees'] ?></b></div></td>
                </tr>
            </table>

            <div id="terms">
                <span>This is System generated Receipt. Signature is not required.</span>
            </div>

        </div>

    </body>

</html>