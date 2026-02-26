<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;" />
    <title>Marriage License Application</title>

    <style>
        @page {
            size: 8.5in 14in;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Libre Baskerville", Courier, monospace;
            font-size: 11pt;
            color: black;
        }

        /* MAIN PAPER */
        .print-container {
            position: relative;
            width: 8.5in;
            height: 14in;
        }

        /* BACKGROUND IMAGE */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 8.5in;
            height: 14in;
            opacity: 1;
            z-index: 1;
        }

        /* TEXT LAYER */
        .data {
            position: absolute;
            white-space: nowrap;
            z-index: 2;
            /* Increased to ensure text is above image */
        }

        /* ---------------- POSITIONS ---------------- */

        /* Top Header Section */
        .province {
            top: 115px;
            left: 175px;
        }

        .municipality {
            top: 135px;
            left: 230px;
        }

        .received-by {
            top: 160px;
            left: 185px;
        }

        .date {
            top: 175px;
            left: 195px;
        }

        /* Row 1: Full Names */
        .b-fullname {
            top: 270px;
            left: 130px;
            font-weight: bold;
        }

        .g-fullname {
            top: 270px;
            left: 450px;
            font-weight: bold;
        }

        /* Row 2: First, Middle, Last Names */
        .g-name-first,
        .b-name-first {
            top: 358px;
        }

        .g-name-middle,
        .b-name-middle {
            top: 373px;
        }

        .g-name-last,
        .b-name-last {
            top: 390px;
        }

        /* Row 3: Birthday & Age */
        .g-bday,
        .b-bday {
            top: 425px;
        }

        .g-age,
        .b-age {
            top: 425px;
        }

        /* Row 4: Birthplace */
        .g-birthplace,
        .b-birthplace {
            top: 460px;
            width: 300px;
            white-space: normal;
        }

        /* Row 5: Sex & Citizenship */
        .g-sex,
        .b-sex {
            top: 495px;
        }

        .g-citizenship,
        .b-citizenship {
            top: 495px;
        }

        /* Row 6: Residence */
        .g-residence,
        .b-residence {
            top: 530px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        /* Row 7: Religion, Civil Status, Marriage Info */
        .g-religion,
        .b-religion {
            top: 570px;
        }

        .g-civil-status,
        .b-civil-status {
            top: 605px;
        }

        .g-if-married,
        .b-if-married {
            top: 640px;
        }

        /* Row 8: Dissolution Info */
        .g-place-dissolved,
        .b-place-dissolved {
            top: 680px;
        }

        .g-date-dissolved,
        .b-date-dissolved {
            top: 720px;
        }

        .g-relationship,
        .b-relationship {
            top: 755px;
        }

        /* Row 9: Father's Info */
        .g-fathername,
        .b-fathername {
            top: 790px;
        }

        .g-father-citizenship,
        .b-father-citizenship {
            top: 825px;
        }

        .g-father-residence,
        .b-father-residence {
            top: 860px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        /* Row 10: Mother's Info */
        .g-mothername,
        .b-mothername {
            top: 910px;
        }

        .g-mother-citizenship,
        .b-mother-citizenship {
            top: 935px;
        }

        .g-mother-residence,
        .b-mother-residence {
            top: 963px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        /* Row 11: Consent Section */
        .g-give-consent,
        .b-give-consent {
            top: 1000px;
        }

        .g-give-consent-relationship,
        .b-give-consent-relationship {
            top: 1025px;
        }

        .g-give-consent-citizenship,
        .b-give-consent-citizenship {
            top: 1050px;
        }

        .g-give-consent-residence,
        .b-give-consent-residence {
            top: 1085px;
            width: 250px;
            font-size: 9pt;
            white-space: normal;
            text-align: center;
        }

        .g-fullname-signature,
        .b-fullname-signature {
            top: 1130px;
        }

        .g-day-today,
        .b-day-today {
            top: 1180px;
        }

        .g-month-today,
        .b-month-today {
            top: 1180px;
        }

        .g-year-today,
        .b-year-today {
            top: 1180px;
        }

        .g-place,
        .b-place {
            top: 1195px;
        }

        .g-ID,
        .b-ID {
            top: 1210px;
            font-size: 9px;
            width: 105px;
            text-align: left;
            white-space: normal;
        }

        .g-Issued-on,
        .b-Issued-on {
            top: 1227px;
        }

        .g-Issued-at,
        .b-Issued-at {
            top: 1244px;
        }


        .g-civil-registrar,
        .b-civil-registrar {
            top: 1275px;
        }

        /* ---------------- LEFT OFFSETS (COLUMN ALIGNMENT) ---------------- */

        /* Groom Left Offsets */
        .g-name-first,
        .g-name-middle,
        .g-name-last,
        .g-birthplace {
            left: 165px;
        }

        /* Groom's Birthday */
        .g-bday {
            left: 130px;
        }

        .g-day {
            left: 120px;
        }

        .g-month {
            left: 170px;
        }

        .g-year {
            left: 275px;
        }

        /* Age */
        .g-age {
            left: 350px;
        }

        .g-sex {
            left: 145px;
        }

        /* Citezenship */
        .g-citizenship {
            left: 270px;
        }

        .g-birth-municipality {
            left: 125px;
        }

        .g-birth-province {
            left: 225px;
        }

        .g-birth-country {
            left: 290px;
        }

        /* Residence */
        .g-residence {
            left: 115px;
        }

        .g-religion {
            left: 190px;
        }

        .g-civil-status {
            left: 220px;
        }

        .g-if-married {
            left: 210px;
        }

        .g-place-dissolved {
            left: 140px;
        }

        .g-place-municipality {
            left: 125px;
        }

        .g-place-province {
            left: 225px;
        }

        .g-place-country {
            left: 280px;
        }

        .g-date-dissolved {
            left: 160px;
        }

        .g-disolve-day {
            left: 120px;
        }

        .g-disolve-month {
            left: 210px;
        }

        .g-disolve-year {
            left: 310px;
        }

        .g-relationship {
            left: 190px;
        }

        .g-fathername,
        .g-mothername {
            left: 145px;
        }

        .g-f-fname,
        .g-m-fname {
            left: 130px;
        }

        .g-f-mname,
        .g-m-mname {
            left: 220px;
        }

        .g-f-lname,
        .g-m-lname {
            left: 300px;
        }

        .g-father-citizenship,
        .g-mother-citizenship,
        .g-give-consent,
        .g-give-consent-relationship,
        .g-give-consent-citizenship {
            left: 200px;
        }

        .g-father-residence,
        .g-mother-residence,
        .g-give-consent-residence {
            left: 135px;
        }

        .g-fullname-signature {
            left: 190px;
        }

        .g-day-today {
            left: 115px;
        }

        .g-month-today {
            left: 200px;
        }

        .g-year-today {
            left: 330px;
        }

        .g-place {
            left: 135px;
        }

        .g-ID {
            left: 265px;
        }


        .g-Issued-on {
            left: 140px;
        }

        .g-issued-month {
            left: 160px;

        }

        .g-issued-day {
            left: 305px;
        }

        .g-issued-year {
            left: 340px;
        }

        .g-Issued-at {
            left: 140px;
        }

        .g-civil-registrar {
            left: 135px;
        }

        /* Bride Left Offsets */
        .b-name-first,
        .b-name-middle,
        .b-name-last,
        .b-birthplace {
            left: 550px;
        }

        .b-bday {
            left: 530px;
        }

        .b-day {
            left: 495px;
        }

        .b-month {
            left: 550px;
        }

        .b-year {
            left: 650px;
        }

        .b-age {
            left: 720px;
        }

        .b-sex {
            left: 530px;
        }

        .b-citizenship {
            left: 650px;
        }

        .b-birth-municipality {
            left: 500px;
        }

        .b-birth-province {
            left: 600px;
        }

        .b-birth-country {
            left: 655px;
        }


        .b-residence {
            left: 500px;
        }

        .b-religion {
            left: 550px;
        }

        .b-civil-status {
            left: 580px;
        }

        .b-if-married {
            left: 570px;
        }

        .b-place-dissolved {
            left: 500px;
        }

        .b-disolve-municipality {
            left: 495px;
        }

        .b-disolve-province {
            left: 600px;
        }

        .b-disolve-country {
            left: 665px;
        }

        .b-date-dissolved {
            left: 540px;
        }

        .b-date-dissolved-day {
            left: 500px;
        }

        .b-date-dissolved-month {
            left: 580px;
        }

        .b-date-dissolved-year {
            left: 680px;
        }

        .b-relationship {
            left: 560px;
        }

        .b-fathername,
        .b-mothername {
            left: 525px;
        }

        .b-father-first,
        .b-mother-first {
            left: 500px;
        }

        .b-father-middle,
        .b-mother-middle {
            left: 590px;
        }

        .b-father-last,
        .b-mother-last {
            left: 680px;
        }

        .b-father-citizenship,
        .b-mother-citizenship {
            left: 590px;
        }

        .b-father-residence {
            left: 485px;
        }

        .b-mother-residence {
            left: 500px;
        }

        .b-give-consent,
        .b-give-consent-relationship,
        .b-give-consent-citizenship {
            left: 550px;
        }

        .b-give-consent-residence {
            left: 490px;
        }

        .b-fullname-signature {
            left: 560px;
        }

        .b-day-today {
            left: 480px;
        }

        .b-month-today {
            left: 570px;
        }

        .b-year-today {
            left: 690px;
        }

        .b-place {
            left: 530px;
        }

        .b-ID {
            left: 635px;
        }


        .b-Issued-on {
            left: 540px;
        }

        .b-Issued-month {
            left: 520px;
        }

        .b-Issued-day {
            left: 670px;
        }

        .b-Issued-year {
            left: 710px;
        }

        .b-Issued-at {
            left: 540px;
        }

        .b-civil-registrar {
            left: 500px;
        }
    </style>
</head>

<body>
    <div class="print-container">
    
        @unless($transparentBackground)
        <img src="license.jpg" class="bg-image" alt="Background">
        @endunless
    
        @php
        $splitWords = function ($value, $count = 3) {
        $clean = trim((string) ($value ?? ''));
        if ($clean === '') {
        return array_fill(0, $count, '');
        }
        $parts = preg_split('/\s+/', str_replace(',', '', $clean)) ?: [];
        $parts = array_values(array_filter($parts, fn ($part) => trim($part) !== ''));
        return array_pad(array_slice($parts, 0, $count), $count, '');
        };
        $splitComma = function ($value, $count = 3) {
        $clean = trim((string) ($value ?? ''));
        if ($clean === '') {
        return array_fill(0, $count, '');
        }
        $parts = array_map('trim', explode(',', $clean));
        $parts = array_values(array_filter($parts, fn ($part) => trim($part) !== ''));
        return array_pad(array_slice($parts, 0, $count), $count, '');
        };
        [$gBirthDay, $gBirthMonth, $gBirthYear] = $splitWords($groom['birth_date'] ?? '', 3);
        [$bBirthDay, $bBirthMonth, $bBirthYear] = $splitWords($bride['birth_date'] ?? '', 3);
        [$gDissolveDay, $gDissolveMonth, $gDissolveYear] = $splitWords($groom['date_dissolved'] ?? '', 3);
        [$bDissolveDay, $bDissolveMonth, $bDissolveYear] = $splitWords($bride['date_dissolved'] ?? '', 3);
        [$gIssuedMonth, $gIssuedDay, $gIssuedYear] = $splitWords($groom['issued_on'] ?? '', 3);
        [$bIssuedMonth, $bIssuedDay, $bIssuedYear] = $splitWords($bride['issued_on'] ?? '', 3);
        [$gBirthMunicipality, $gBirthProvince, $gBirthCountry] = $splitComma($groom['birthplace'] ?? '', 3);
        [$bBirthMunicipality, $bBirthProvince, $bBirthCountry] = $splitComma($bride['birthplace'] ?? '', 3);
        [$gDissolveMunicipality, $gDissolveProvince, $gDissolveCountry] = $splitComma($groom['place_dissolved'] ?? '', 3);
        [$bDissolveMunicipality, $bDissolveProvince, $bDissolveCountry] = $splitComma($bride['place_dissolved'] ?? '', 3);
        [$gFatherFirst, $gFatherMiddle, $gFatherLast] = $splitWords($groom['father_name'] ?? '', 3);
        [$bFatherFirst, $bFatherMiddle, $bFatherLast] = $splitWords($bride['father_name'] ?? '', 3);
        [$gMotherFirst, $gMotherMiddle, $gMotherLast] = $splitWords($groom['mother_name'] ?? '', 3);
        [$bMotherFirst, $bMotherMiddle, $bMotherLast] = $splitWords($bride['mother_name'] ?? '', 3);
        @endphp
    
        <div class="data province">{{ $meta['province'] ?? '' }}</div>
        <div class="data municipality">{{ $meta['municipality'] ?? '' }}</div>
        <div class="data received-by">{{ $meta['received_by'] ?? '' }}</div>
        <div class="data date">{{ $meta['date'] ?? '' }}</div>
    
        <div class="data b-fullname">{{ $bride['full_name'] ?? '' }}</div>
        <div class="data g-name-first">{{ $groom['name_first'] ?? '' }}</div>
        <div class="data g-name-middle">{{ $groom['name_middle'] ?? '' }}</div>
        <div class="data g-name-last">{{ $groom['name_last'] ?? '' }}</div>
        <div class="data g-bday g-day">{{ $gBirthDay ?? '' }}</div>
        <div class="data g-bday g-month">{{ $gBirthMonth ?? '' }}</div>
        <div class="data g-bday g-year">{{ $gBirthYear ?? '' }}</div>
        <div class="data g-age">{{ $groom['age'] ?? '' }}</div>
        <div class="data g-birthplace g-birth-municipality">{{ $gBirthMunicipality ?? '' }}</div>
        <div class="data g-birthplace g-birth-province">{{ $gBirthProvince ?? '' }}</div>
        <div class="data g-birthplace g-birth-country">{{ $gBirthCountry ?? '' }}</div>
        <div class="data g-sex">{{ $groom['sex'] ?? '' }}</div>
        <div class="data g-citizenship">{{ $groom['citizenship'] ?? '' }}</div>
        <div class="data g-residence">{{ $groom['residence'] ?? '' }}</div>
        <div class="data g-religion">{{ $groom['religion'] ?? '' }}</div>
        <div class="data g-civil-status">{{ $groom['civil_status'] ?? '' }}</div>
        <div class="data g-if-married">{{ $groom['if_married'] ?? '' }}</div>
        <div class="data g-place-dissolved g-place-municipality">{{ $gDissolveMunicipality ?? '' }}</div>
        <div class="data g-place-dissolved g-place-province">{{ $gDissolveProvince ?? '' }}</div>
        <div class="data g-place-dissolved g-place-country">{{ $gDissolveCountry ?? '' }}</div>
        <div class="data g-date-dissolved g-disolve-day">{{ $gDissolveDay ?? '' }}</div>
        <div class="data g-date-dissolved g-disolve-month">{{ $gDissolveMonth ?? '' }}</div>
        <div class="data g-date-dissolved g-disolve-year">{{ $gDissolveYear ?? '' }}</div>
        <div class="data g-relationship">{{ $groom['relationship'] ?? '' }}</div>
        <div class="data g-fathername g-f-fname">{{ $gFatherFirst ?? '' }}</div>
        <div class="data g-fathername g-f-mname">{{ $gFatherMiddle ?? '' }}</div>
        <div class="data g-fathername g-f-lname">{{ $gFatherLast ?? '' }}</div>
        <div class="data g-father-citizenship">{{ $groom['father_citizenship'] ?? '' }}</div>
        <div class="data g-father-residence">{{ $groom['father_residence'] ?? '' }}</div>
        <div class="data g-mothername g-m-fname">{{ $gMotherFirst ?? '' }}</div>
        <div class="data g-mothername g-m-mname">{{ $gMotherMiddle ?? '' }}</div>
        <div class="data g-mothername g-m-lname">{{ $gMotherLast ?? '' }}</div>
        <div class="data g-mother-citizenship">{{ $groom['mother_citizenship'] ?? '' }}</div>
        <div class="data g-mother-residence">{{ $groom['mother_residence'] ?? '' }}</div>
        <div class="data g-give-consent">{{ $groom['give_consent'] ?? '' }}</div>
        <div class="data g-give-consent-relationship">{{ $groom['give_consent_relationship'] ?? '' }}</div>
        <div class="data g-give-consent-citizenship">{{ $groom['give_consent_citizenship'] ?? '' }}</div>
        <div class="data g-give-consent-residence">{{ $groom['give_consent_residence'] ?? '' }}</div>
        <div class="data g-fullname-signature">{{ $groom['fullname_signature'] ?? '' }}</div>
        <div class="data g-day-today">{{ $groom['day_today'] ?? '' }}</div>
        <div class="data g-month-today">{{ $groom['month_today'] ?? '' }}</div>
        <div class="data g-year-today">{{ $groom['year_today'] ?? '' }}</div>
        <div class="data g-place">{{ $groom['place'] ?? '' }}</div>
        <div class="data g-ID">{{ $groom['id'] ?? '' }}</div>
        <div class="data g-Issued-on g-issued-month">{{ $gIssuedMonth ?? '' }}</div>
        <div class="data g-Issued-on g-issued-day">{{ $gIssuedDay ?? '' }}</div>
        <div class="data g-Issued-on g-issued-year">{{ $gIssuedYear ?? '' }}</div>
        <div class="data g-Issued-at">{{ $groom['issued_at'] ?? '' }}</div>
        <div class="data g-civil-registrar">{{ $groom['civil_registrar'] ?? '' }}</div>
    
        <div class="data g-fullname">{{ $groom['full_name'] ?? '' }}</div>
        <div class="data b-name-first">{{ $bride['name_first'] ?? '' }}</div>
        <div class="data b-name-middle">{{ $bride['name_middle'] ?? '' }}</div>
        <div class="data b-name-last">{{ $bride['name_last'] ?? '' }}</div>
        <div class="data b-bday b-day">{{ $bBirthDay ?? '' }}</div>
        <div class="data b-bday b-month">{{ $bBirthMonth ?? '' }}</div>
        <div class="data b-bday b-year">{{ $bBirthYear ?? '' }}</div>
        <div class="data b-age">{{ $bride['age'] ?? '' }}</div>
        <div class="data b-birthplace b-birth-municipality">{{ $bBirthMunicipality ?? '' }}</div>
        <div class="data b-birthplace b-birth-province">{{ $bBirthProvince ?? '' }}</div>
        <div class="data b-birthplace b-birth-country">{{ $bBirthCountry ?? '' }}</div>
        <div class="data b-sex">{{ $bride['sex'] ?? '' }}</div>
        <div class="data b-citizenship">{{ $bride['citizenship'] ?? '' }}</div>
        <div class="data b-residence">{{ $bride['residence'] ?? '' }}</div>
        <div class="data b-religion">{{ $bride['religion'] ?? '' }}</div>
        <div class="data b-civil-status">{{ $bride['civil_status'] ?? '' }}</div>
        <div class="data b-if-married">{{ $bride['if_married'] ?? '' }}</div>
        <div class="data b-place-dissolved b-disolve-municipality">{{ $bDissolveMunicipality ?? '' }}</div>
        <div class="data b-place-dissolved b-disolve-province">{{ $bDissolveProvince ?? '' }}</div>
        <div class="data b-place-dissolved b-disolve-country">{{ $bDissolveCountry ?? '' }}</div>
        <div class="data b-date-dissolved b-date-dissolved-day">{{ $bDissolveDay ?? '' }}</div>
        <div class="data b-date-dissolved b-date-dissolved-month">{{ $bDissolveMonth ?? '' }}</div>
        <div class="data b-date-dissolved b-date-dissolved-year">{{ $bDissolveYear ?? '' }}</div>
        <div class="data b-relationship">{{ $bride['relationship'] ?? '' }}</div>
        <div class="data b-fathername b-father-first">{{ $bFatherFirst ?? '' }}</div>
        <div class="data b-fathername b-father-middle">{{ $bFatherMiddle ?? '' }}</div>
        <div class="data b-fathername b-father-last">{{ $bFatherLast ?? '' }}</div>
        <div class="data b-father-citizenship">{{ $bride['father_citizenship'] ?? '' }}</div>
        <div class="data b-father-residence">{{ $bride['father_residence'] ?? '' }}</div>
        <div class="data b-mothername b-mother-first">{{ $bMotherFirst ?? '' }}</div>
        <div class="data b-mothername b-mother-middle">{{ $bMotherMiddle ?? '' }}</div>
        <div class="data b-mothername b-mother-last">{{ $bMotherLast ?? '' }}</div>
        <div class="data b-mother-citizenship">{{ $bride['mother_citizenship'] ?? '' }}</div>
        <div class="data b-mother-residence">{{ $bride['mother_residence'] ?? '' }}</div>
        <div class="data b-give-consent">{{ $bride['give_consent'] ?? '' }}</div>
        <div class="data b-give-consent-relationship">{{ $bride['give_consent_relationship'] ?? '' }}</div>
        <div class="data b-give-consent-citizenship">{{ $bride['give_consent_citizenship'] ?? '' }}</div>
        <div class="data b-give-consent-residence">{{ $bride['give_consent_residence'] ?? '' }}</div>
        <div class="data b-fullname-signature">{{ $bride['fullname_signature'] ?? '' }}</div>
        <div class="data b-day-today">{{ $bride['day_today'] ?? '' }}</div>
        <div class="data b-month-today">{{ $bride['month_today'] ?? '' }}</div>
        <div class="data b-year-today">{{ $bride['year_today'] ?? '' }}</div>
        <div class="data b-place">{{ $bride['place'] ?? '' }}</div>
        <div class="data b-ID">{{ $bride['id'] ?? '' }}</div>
        <div class="data b-Issued-on b-Issued-month">{{ $bIssuedMonth ?? '' }}</div>
        <div class="data b-Issued-on b-Issued-day">{{ $bIssuedDay ?? '' }}</div>
        <div class="data b-Issued-on b-Issued-year">{{ $bIssuedYear ?? '' }}</div>
        <div class="data b-Issued-at">{{ $bride['issued_at'] ?? '' }}</div>
        <div class="data b-civil-registrar">{{ $bride['civil_registrar'] ?? '' }}</div>
    
    </div>
</body>

</html>
