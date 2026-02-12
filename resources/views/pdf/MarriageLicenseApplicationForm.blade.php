<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        /* 1. PAPER CONFIGURATION */
        @page {
            size: 8.5in 14in;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            /* Use a font that mimics typewriter or clear print */
            font-family: "Courier New", Courier, monospace;
            font-size: 11pt;
            color: black;


        }

        /* 2. THE ALIGNMENT CONTAINER */
        .print-container {
            position: relative;
            width: 8.5in;
            height: 14in;
            box-sizing: border-box;



            /* TO CALIBRATE:
               1. Scan your form as a JPG.
               2. Uncomment the line below.
               3. Use browser 'Inspect' to move text into the boxes. */
            background: url('license.jpg') no-repeat;
            background-size: contain;
        }

        /* 3. PRECISION POSITIONING ENGINE */
        .data {
            position: absolute;
            white-space: nowrap;
            /* border: 1px solid red; /* Uncomment to see the 'box' during setup */
        }

        /* ---------------------------------------------------------
           COORDINATE MAPPING (Adjust these values in millimeters)
           These are sample placements based on Municipal Form 90
           --------------------------------------------------------- */

        /* Top Section */
        /* .registry-no {
            top: 128px;
            left: 555px;
            font-size: 14pt;
            font-weight: bold;
        } */

        .province {
            top: 105px;
            left: 175px;
        }

        .municipality {
            top: 125px;
            left: 230px;
        }

        .received-by {
            top: 145px;
            left: 185px;
        }

        .date {
            top: 163px;
            left: 195px;
        }

        /* Groom Column (Left) */

        .b-fullname {
            top: 247px;
            left: 130px;
            font-size: 11pt;
            font-weight: bold;
        }

        .g-name-first {
            top: 328px;
            left: 155px;
        }

        .g-name-middle {
            top: 343px;
            left: 155px;
        }

        .g-name-last {
            top: 360px;
            left: 155px;
        }

        .g-bday {
            top: 395px;
            left: 130px;
        }

        .g-age {
            top: 395px;
            left: 350px;
        }

        .g-birthplace {
            top: 430px;
            left: 155px;
            width: 300px;
            font-size: 9pt;
            white-space: normal;
        }

        .g-sex {
            top: 460px;
            left: 145px;
        }

        .g-citizenship {
            top: 460px;
            left: 270px;
        }

        .g-residence {
            top: 495px;
            left: 115px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .g-religion {
            top: 530px;
            left: 180px;
        }

        .g-civil-status {
            top: 560px;
            left: 230px;
        }

        .g-if-married {
            top: 590px;
            left: 230px;
        }

        .g-place-dissolved {
            top: 630px;
            left: 140px;
        }

        .g-date-dissolved {
            top: 670px;
            left: 160px;
        }

        .g-relationship {
            top: 700px;
            left: 170px;
        }

        .g-fathername {
            top: 740px;
            left: 145px;
        }

        .g-father-citizenship {
            top: 765px;
            left: 200px;
        }

        .g-father-residence {
            top: 800px;
            left: 135px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .g-mothername {
            top: 840px;
            left: 145px;
        }

        .g-mother-citizenship {
            top: 865px;
            left: 200px;
        }

        .g-mother-residence {
            top: 893px;
            left: 135px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .g-give-consent {
            top: 930px;
            left: 200px;
        }

        .g-give-consent-relationship {
            top: 950px;
            left: 200px;
        }

        .g-give-consent-citizenship {
            top: 975px;
            left: 200px;
        }

        .g-give-consent-residence {
            top: 1010px;
            left: 135px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        /* Bride Column (Right) */
        .g-fullname {
            top: 247px;
            left: 450px;
            font-size: 11pt;
            font-weight: bold;
        }

        .b-name-first {
            top: 328px;
            left: 550px;
        }

        .b-name-middle {
            top: 343px;
            left: 550px;
        }

        .b-name-last {
            top: 360px;
            left: 550px;
        }

        .b-bday {
            top: 395px;
            left: 530px;
        }

        .b-age {
            top: 395px;
            left: 720px;
        }

        .b-birthplace {
            top: 430px;
            left: 535px;
            width: 300px;
            font-size: 9pt;
            white-space: normal;
        }

        .b-sex {
            top: 460px;
            left: 530px;
        }

        .b-citizenship {
            top: 460px;
            left: 650px;
        }

        .b-residence {
            top: 495px;
            left: 500px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .b-religion {
            top: 530px;
            left: 580px;
        }

        .b-civil-status {
            top: 560px;
            left: 580px;
        }

        .b-if-married {
            top: 590px;
            left: 580px;
        }

        .b-place-dissolved {
            top: 630px;
            left: 500px;
        }

        .b-date-dissolved {
            top: 670px;
            left: 560px;
        }

        .b-relationship {
            top: 700px;
            left: 570px;
        }

        .b-fathername {
            top: 740px;
            left: 525px;
        }

        .b-father-citizenship {
            top: 765px;
            left: 600px;
        }

        .b-father-residence {
            top: 800px;
            left: 485px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .b-mothername {
            top: 840px;
            left: 525px;
        }

        .b-mother-citizenship {
            top: 865px;
            left: 600px;
        }

        .b-mother-residence {
            top: 890px;
            left: 500px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .b-give-consent {
            top: 930px;
            left: 550px;
        }

        .b-give-consent-relationship {
            top: 950px;
            left: 550px;
        }

        .b-give-consent-citizenship {
            top: 975px;
            left: 550px;
        }

        .b-give-consent-residence {
            top: 1010px;
            left: 500px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        /* Hide background for final print */
        @media print {
            .print-container {
                background: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="print-container">
        <!-- <div class="data registry-no">{{ $registry_no }}</div> -->
        <div class="data province">LEYTE</div>
        <div class="data municipality">ABUYOG</div>
        <div class="data received-by">MUNICIPAL CIVIL REGISTRAR</div>
        <div class="data date">Feb 10, 2026</div>

        <!-- GROOM'S DETAILS -->
        <div class="data b-fullname">Russell Limos Taburada</div>

        <div class="data g-name-first">Ruwilson</div>
        <div class="data g-name-middle">Taburada</div>
        <div class="data g-name-last">Hipos</div>
        <div class="data g-bday">November 26, 2003</div>
        <div class="data g-age">22</div>
        <div class="data g-birthplace">Abuyog Leyte, Philippines</div>
        <div class="data g-sex">Male</div>
        <div class="data g-citizenship">Filipino</div>
        <div class="data g-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data g-religion">Roman Catholic</div>
        <div class="data g-civil-status">Single</div>
        <div class="data g-if-married">Divorced</div>
        <div class="data g-place-dissolved">Abuyog Leyte, Philippines</div>
        <div class="data g-date-dissolved">February 10, 2026</div>
        <div class="data g-relationship">Not Applicable</div>
        <div class="data g-fathername">Jaymar Sabillo Balansag</div>
        <div class="data g-father-citizenship">Filipino</div>
        <div class="data g-father-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data g-mothername">Lorena Limos Taburada</div>
        <div class="data g-mother-citizenship">Filipino</div>
        <div class="data g-mother-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data g-give-consent">NOT APPLICABLE</div>
        <div class="data g-give-consent-relationship">NOT APPLICABLE</div>
        <div class="data g-give-consent-citizenship">NOT APPLICABLE</div>
        <div class="data g-give-consent-residence">NOT APPLICABLE</div>

        <!-- BRIDE'S DETAILSs -->
        <div class="data g-fullname">Ruwilson Taburada Hipos</div>

        <div class="data b-name-first">Ruwilson</div>
        <div class="data b-name-middle">Taburada</div>
        <div class="data b-name-last">Hipos</div>
        <div class="data b-bday">November 26, 2003</div>
        <div class="data b-age">22</div>
        <div class="data b-birthplace">Abuyog Leyte, Philippines</div>
        <div class="data b-sex">Male</div>
        <div class="data b-citizenship">Filipino</div>
        <div class="data b-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data b-religion">Roman Catholic</div>
        <div class="data b-civil-status">Single</div>
        <div class="data b-if-married">Divorced</div>
        <div class="data b-place-dissolved">Abuyog Leyte, Philippines</div>
        <div class="data b-date-dissolved">February 10, 2026</div>
        <div class="data b-relationship">Not Applicable</div>
        <div class="data b-fathername">Jaymar Sabillo Balansag</div>
        <div class="data b-father-citizenship">Filipino</div>
        <div class="data b-father-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data b-mothername">Lorena Limos Taburada</div>
        <div class="data b-mother-citizenship">Filipino</div>
        <div class="data b-mother-residence">340 Ave. Rizal Street, Brgy. Bito Abuyog Leyte, Philippines</div>
        <div class="data b-give-consent">NOT APPLICABLE</div>
        <div class="data b-give-consent-relationship">NOT APPLICABLE</div>
        <div class="data b-give-consent-citizenship">NOT APPLICABLE</div>
        <div class="data b-give-consent-residence">NOT APPLICABLE</div>
    </div>

</body>

</html>