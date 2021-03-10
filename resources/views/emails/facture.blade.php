<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400,500,600,700" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors], /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
            color: inherit !important;
        }

        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u ~ div .email-container {
                min-width: 320px !important;
            }
        }

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u ~ div .email-container {
                min-width: 375px !important;
            }
        }

        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u ~ div .email-container {
                min-width: 414px !important;
            }
        }
    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

        .primary {
            background: #17bebb;
        }

        .bg_white {
            background: #ffffff;
        }

        .bg_light {
            background: #f7fafa;
        }

        .bg_black {
            background: #000000;
        }

        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .email-section {
            padding: 2.5em;
        }

        /*BUTTON*/
        .btn {
            padding: 10px 15px;
            display: inline-block;
        }

        .btn.btn-primary {
            border-radius: 5px;
            background: #17bebb;
            color: #ffffff;
        }

        .btn.btn-white {
            border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }

        .btn.btn-white-outline {
            border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }

        .btn.btn-black-outline {
            border-radius: 0px;
            background: transparent;
            border: 2px solid #000;
            color: #000;
            font-weight: 700;
        }

        .btn-custom {
            color: rgba(0, 0, 0, .3);
            text-decoration: underline;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Work Sans', sans-serif;
            color: #000000;
            margin-top: 0;
            font-weight: 400;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }

        a {
            color: #17bebb;
        }

        table {
        }

        /*LOGO*/

        .logo h1 {
            margin: 0;
        }

        .logo h1 a {
            color: #17bebb;
            font-size: 24px;
            font-weight: 700;
            font-family: 'Work Sans', sans-serif;
        }

        /*HERO*/
        .hero {
            position: relative;
            z-index: 0;
        }

        .hero .text {
            color: rgba(0, 0, 0, .3);
        }

        .hero .text h2 {
            color: #000;
            font-size: 34px;
            margin-bottom: 15px;
            font-weight: 300;
            line-height: 1.2;
        }

        .hero .text h3 {
            font-size: 24px;
            font-weight: 200;
        }

        .hero .text h2 span {
            font-weight: 600;
            color: #000;
        }

        /*PRODUCT*/
        .product-entry {
            display: block;
            position: relative;
            float: left;
            padding-top: 20px;
        }

        .product-entry .text {
            width: calc(100% - 125px);
            padding-left: 20px;
        }

        .product-entry .text h3 {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .product-entry .text p {
            margin-top: 0;
        }

        .product-entry img, .product-entry .text {
            float: left;
        }

        ul.social {
            padding: 0;
        }

        ul.social li {
            display: inline-block;
            margin-right: 10px;
        }

        /*FOOTER*/

        .footer {
            border-top: 1px solid rgba(0, 0, 0, .05);
            color: rgba(0, 0, 0, .5);
        }

        .footer .heading {
            color: #000;
            font-size: 20px;
        }

        .footer ul {
            margin: 0;
            padding: 0;
        }

        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: rgba(0, 0, 0, 1);
        }

        @media screen and (max-width: 600px) {

        }

        .profile {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        * {
            font-size: 12px;
            font-family: 'DejaVu Sans', serif;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.producto,
        th.producto {
            width: 75px;
            max-width: 75px;
        }

        td.cantidad,
        th.cantidad {
            width: 50px;
            max-width: 50px;
            word-break: break-all;
        }

        td.precio,
        th.precio {
            width: 50px;
            max-width: 50px;
            word-break: break-all;
        }

        .centrado {
            text-align: center;
            align-content: center;
        }

        #ticket {
            padding-top: 5px;
            align-content: center;
            width: 75mm;
            max-width: 75mm;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

    </style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
<center style="width: 100%; background-color: #f1f1f1;">
    <div
        style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
        <!-- BEGIN BODY -->
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
               style="margin: auto;">

            <div
                id="ticket"
                class="ticket"
            >
                @if($editSale['company']->logo)
                    <img
                        style="margin-top: 15px"
                        class="profile mx-auto d-block"
                        src="{{$editSale['company']->logo}}"
                        alt="LOGO"
                    >
                @endif
                <p class="centrado">Comprobante<br>
                    <b><i>{{ $editSale['shop']->name }}</i></b><br>
                    <b>'No. Factura'</b>: {{ $editSale['no_facture'] }}<br>
                    <b> "Vendido por: "</b>
                    {{ $editSale['create']->firstName }}
                    {{ $editSale['create']->firstName ? $editSale['create']->lastName : ""}}<br>
                    <b> "Caja: " </b>
                    {{ $editSale['box']->name }}<br>
                    <b>'Moneda: '</b>
                    {{ $editSale['company']->currency }}<br>
                    {{$editSale['updated_at']}}<br>
                    {{ $editSale['company']->slogan ? strtoupper($editSale['company']->slogan): ""}}
                </p>
                <div style="width: 100%; margin-bottom: 20px">
                    <v-col
                        style="width: 100%;padding: inherit"
                        cols="12"
                        md="12"
                    >
                        <table style="width: 100%; border-bottom-style: dashed;border-top-style:dashed ">
                            <tr>
                                <th class="cantidad">
                                    CANT
                                </th>
                                <th class="producto">
                                    ART
                                </th>
                                <th class="precio">
                                    $$
                                </th>
                            </tr>
                        </table>
                        <p style="margin-top: 15px; text-align: center;margin-bottom: 10px;">
                            ***********************************
                        </p>
                    </v-col>
                    @foreach($editSale['articles'] as $i=>$art)
                    <v-col style="width: 100%;padding: inherit"
                           cols="12"
                           md="12"
                    >
                        <table style="width: 100%">
                            <tbody>
                            <tr>
                                <td>{{ $art->cant }}</td>
                                <td>{{ $art->name }}</td>
                                <td>
                                    {{  round($art->cant*$art->price,2) }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="3"
                                    style="width: 100%"
                                >
                                    @if(count($art->discount) >0){
                                    <table
                                        style="width: 100%"
                                    >
                                        <tbody>
                                        <tr
                                            v-for="(lDiscount, j) of art.discount"
                                            :key="j"
                                        >
                                            <td>
                                                Descuento
                                            </td>
                                            <td>
                                                {{ lDiscount.name }}{{ lDiscount.percent ? '('.lDiscount.value .'%) ':' ' }}
                                            </td>
                                            <td>
                                                <i>
                                                    -{{ lDiscount.percent ? round(lDiscount.value*art.cant*art.price/100, 2) : round(lDiscount.value, 2) }}
                                                </i>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    }
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td
                                    colspan="3"
                                    style="width: 100%"
                                >
                                    @if(count($art->taxes) > 0)
                                        <table
                                            style="width: 100%"
                                        >
                                            <tbody>
                                            @foreach($art->taxes as $j=>$tax){
                                            <tr
                                            >
                                                <td>
                                                    Impuesto
                                                </td>
                                                <td>
                                                    {{ $tax->name .($tax->percent ? '('.$tax->value .'%) ':' ')}}
                                                </td>
                                                <td>
                                                    <i>
                                                        +{{ $tax->percent ? round($tax->value*$art->cant*$art->price/100, 2) : round($tax->value,2) }}
                                                    </i>
                                                </td>
                                            </tr>
                                            }@endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td/>
                                <td> TOTAL</td>
                                <td><b>{{$editSale['company']->currency.' '. $art->totalPrice }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </v-col>
                    @endforeach
                </div>
                <b style="margin-top: 10px">Despliegue</b>
                <table style="width: 100%; margin-bottom: 10px">
                    <tbody>
                    <tr>
                        <td><b>SubTotal</b></td>
                        <td style="text-align: right">
                            {{ $editSale['company']->currency.' '. round($art->cant*$art->price, 2) }}
                        </td>
                    </tr>
                    @if(count($editSale['taxes']) !== 0)
                        <p style="font-style: italic;font-family: cursive;">
                            Impuesto por Venta
                        </p>
                        @foreach($editSale['taxes'] as $i=>$tax)
                            <tr
                            >
                                <td><b>Impuesto({{ $tax->name }})</b></td>
                                @if($tax->percent===true)
                                    <td style="text-align: right">
                                        <i>{{ round($tax->value * $art->cant*$art->price / 100, 2) }} ({{ $tax->value }}
                                            %)
                                        </i>
                                    </td>
                                @endif
                                <td
                                    v-else
                                    style="text-align: right"
                                >
                                    <i>{{ $tax->value }}</i>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <template>
                        <!-- <p style="font-style:italic;font-family: cursive;">
                        Descuento General
                        </p>-->
                        @foreach($editSale['discounts'] as $ld=> $disc)
                            <tr
                            >
                                <td><b>{{ $disc->name }}{{ $disc->percent ? '('.$disc->value .'%)':'' }}</b></td>
                                @if($disc->percent==='true')
                                    <td
                                        style="text-align: right"
                                    >
                                        <i>-{{ round($disc->value * $art->cant*$art->price / 100, 2) }}</i>
                                    </td>
                                @endif
                                <td
                                    v-else
                                    style="text-align: right"
                                >
                                    <i>{{ $disc->value }}</i>
                                </td>
                            </tr>
                        @endforeach
                    </template>
                    <tr>
                        <td>
                            <p style="text-transform: uppercase;">
                                <b>Total: </b>
                            </p>
                        </td>
                        <td style="text-align: right;font-size: large;text-decoration: underline;}">
                            {{ $editSale['company']->currency  . ' ' . $editSale['total'] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <b style="margin-top: 10px">Despliegue</b>
                <table style="width: 100%">
                    <tbody>
                    @foreach($editSale->pays as $p=>$pay)
                        <tr
                            class="total"
                        >
                            <td
                                class="price"
                                style="border-right: black"
                            >
                                Pagos:
                            </td>
                            <td
                                class="price"
                                style="text-align: right"
                            >
                                {{ $editSale['company']->currency  .' '. round($pay->cant, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($editSale['cashPays']) > 0){
                <table
                    style="width: 100%"
                >
                    <thead>
                    <tr>
                        <th class="cantidad">
                            CANT
                        </th>
                        <th class="producto">PAGADO
                        </th>
                        <th class="precio">
                            DEVUELTO
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($editSale->cashPays as $h=>$pay){
                    <tr
                    >
                        <td
                            class="cantidad"
                            style="text-align: center"
                        >
                            {{ $pay->method ==='cash'? $editSale->company->currency: '' }} {{ round($pay->cant_pay, 2) }}
                        </td>
                        <td
                            class="producto"
                        >
                            <div>{{ round($pay->cant, 2) }}</div>
                        </td>
                        <td
                            class="price"
                            style="text-align: center"
                        >
                            {{ $pay->cant_back? $editSale['company']->currency:'' }} {{ round($pay->cant_back, 2) }}
                        </td>
                    </tr>
                    }
                    @endforeach
                    </tbody>
                </table>}
                @endif
                <p class="centrado">
                    <i> {{ $editSale['company']->footer ? strtoupper($editSale['company']->footer): '' }}</i><br>
                    'Contactenos: '.{{$editSale['company']->email}}
                </p>
            </div>
        </table>

    </div>
</center>
</body>
</html>
