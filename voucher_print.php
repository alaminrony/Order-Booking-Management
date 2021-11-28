
<!doctype html>

    <html>

    <head>

        <meta charset="utf-8">

        <title>Collection invoice No</title>

        <base href="http://squadron.eposbd.com/"/>

        <meta http-equiv="cache-control" content="max-age=0"/>

        <meta http-equiv="cache-control" content="no-cache"/>

        <meta http-equiv="expires" content="0"/>

        <meta http-equiv="pragma" content="no-cache"/>

        <link rel="shortcut icon" href="http://squadron.eposbd.com/themes/default/assets/images/icon.png"/>

        <link href="http://squadron.eposbd.com/themes/default/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <style type="text/css" media="all">

            body { color: #000; }
            #receipt-data {margin-top: 30px}

            #wrapper { max-width: 660px; margin: 0 auto; padding-top: 100px; }

            .btn { border-radius: 0; margin-bottom: 5px; }

            .table, .table tr, .table th, .table td{  
                border: 1px solid #000 !important;
             } 

            .table th {
               background: #f5f5f5;  
               font-size: 11px;
               }

            h3 { margin: 5px 0; }

            .signature {
               margin: 100px 0px 0px 0px;
            } 
            .authorized{
                border: 1px solid #efefef;
                float: right;
                height: 75px;
                padding: 5px 0 0 8px;
                width: 40%;
            }
            .customer{
                border: 1px solid #efefef;
                float: left;
                height: 75px;
                padding: 5px 8px 0 0 ;
                width: 40%;
                margin-left: 3px;
                }
            .authorized > span , .customer > span  {
                border-top: 1px solid #a0a0a1;
            }
            .authorized span { 
                float: right;
            }
            .warranty {
                font-size: 12px;
            }
            #word-of-amount {
                text-transform: uppercase;
                font-size: 11px;
            }
            .hrber {
                border-bottom: 1px solid #000;
                width: 100%; }
            .text{
                margin-top: -125px !important; 
                font-size:11px;
            }
            .inv-logo {
                text-align: center;
            }
            .inv-logo img {
                width: 100%; 
            }
            .row-item1 {
                height: 225px;
            }
            .text-center{
                text-align: center;
            }
            .text-left{
                text-align: left;
            }
            .text-right{
                text-align: right;
            }

            @media print {

                .no-print { display: none; }

                #wrapper { max-width: 680px; width: 100%; min-width: 330px; margin: 0 auto; }

            } 

        </style> 

    </head>



    <body>
 

<div id="wrapper">

    <div id="receiptData"> 

    <div id="receipt-data">

        <div class="text"> 
            <div class="inv-logo"> 
                        <img src="http://squadron.eposbd.com/themes/default/assets/images/chalan.png" alt="Computer Squadron" /> 
                </div>  
            <h2 style="text-align: center;">Collection Voucher</h2><br>

            <div style="clear:both;"> </div>

            <table class="table table-striped table-bordered" width="800px"> 
                <tbody>
                    <tr>
                        <td><strong>Date time</strong> </td>
                        <td>Thu 3 Dec 2020 07:04 PM</td>
                    </tr>
                    <tr>
                        <td><strong>Collected with thanks from </strong></td>
                        <td><strong><span>ECOURIER LTD. </span>/<span> 8801723878319</span></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Collected By</strong> </td>
                        <td><strong><span>Computer Squadron</span></strong></td>                        
                    </tr>                    
                    <tr>
                        <td><strong>Collected amount<strong></td>
                        <td>8725</td>
                    </tr>
                    <tr>
                        <td><strong>Amount In Words </strong></td>
                        <td><div id="word-of-amount"></div></td>
                    </tr>
                      
                </tbody>
            </table>
            <p class="text-center"></p>  
            </div>
            <div class="signature" >
            <div class="authorized" ><br><br><span>Authorized Signature</span></div>
            <div class="customer"><br><br><span>Customer Signature <span></div>
            </div>
			<div style="clear:both;"></div>
            </div>
			<br>
            <br>
            <div class="well well-sm"> 
                Thanks for being with <b>COMPUTER SQUADRON</b> !!<br>
            </div>

        </div>

        <div style="clear:both;"></div>

    </div> 

</div>

<canvas id="hidden_screenshot" style="display:none;">

</canvas>

<div class="canvas_con" style="display:none;"></div>
</body>
</html>
<script src="http://squadron.eposbd.com/themes/default/assets/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];

var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

 $(document).ready(function () {
    s = 8725;
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    if (x != s.length) {
        var y = s.length;
        str += 'point ';
        for (var i = x + 1; i < y; i++) str += dg[n[i]] + ' ';
    }
     var out =  str.replace(/\s+/g, ' ');
     
     //alert(out);
     //return out;
      $('#word-of-amount').html(out+' Taka Only');  
    

});

</script>
