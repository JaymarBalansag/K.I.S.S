<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Joint Affidavit of Cohabitation</title>
  <style>
    @font-face {
      font-family: "Bookman Old Style";
      src: url('fonts/bookmanoldstyle.ttf') format('truetype');
    }
    @page { size: 8.5in 11in; margin: 0.8in 1in; }
    body { font-family: "Bookman Old Style", serif; color: #000; font-size: 19px; line-height: 1.25; }
    .header { font-size: 16px; margin-bottom: 28px; }
    .title { text-align: center; font-weight: 700; font-size: 24px; margin: 16px 0 28px; }
    .content p { text-align: justify; margin: 0 0 16px; }
    ol { margin: 10px 0 22px 28px; }
    li { margin-bottom: 14px; text-align: justify; }
    .signatures { width: 100%; margin-top: 26px; border-collapse: collapse; }
    .signatures-table { width: 100%; border-collapse: collapse; }
    .sig-cell { width: 50%; vertical-align: top; padding-right: 20px; }
    .sig-cell:last-child { padding-right: 0; }
    .sig-name { font-weight: 700; text-transform: uppercase; border-bottom: 1px solid #000; display: inline-block; width: auto; margin: 0 auto 4px; padding-bottom: 2px; }
    .sig-affiant { display: block; margin-top: 6px; font-size: 19px; text-align: center; }
    .sig-line { display: block; margin-top: 6px; font-size: 14px; line-height: 1.25; }
    .small { font-size: 19px; }
  </style>
</head>
<body>
  <div class="header">
    Republic of the Philippines &nbsp; )<br>
    Province of Leyte &nbsp;&nbsp;     ) S.S<br>
    Municipality of Abuyog &nbsp;      )
  </div>
  <div class="title"><b>JOINT AFFIDAVIT OF COHABITATION</b></div>
  <div class="content">
    <p style="text-indent: 40px;">We, <strong>{{ $groomName }}</strong> and <strong>{{ $brideName }}</strong>, of legal ages, Filipino Citizens, both single (living together) and both residents of <strong>{{ $city }}</strong> having been duly sworn in accordance with law, hereby depose and say:</p>
    <ol>
      <li>That we have been living together as husband and wife under the same roof, continuously and without any interruption, since {{ $monthName ?: '____________' }} {{ $sinceYear ?: '____________' }} or a period of more than (5) years;</li>
      <li>That during our cohabitation and even until present, we remain both of single status and hence, there exists no legal impediment for us to marry each other; and</li>
      <li>As such, we are executing this Affidavit to attest to the foregoing facts and for purposes of contracting marriage without need of securing marriage license pursuant to the provisions of Article 34 of the Family Code of the Philippines for all legal intents and purposes this may serve.</li>
    </ol>
    <p style="text-indent: 40px;">IN WITNESS WHEREOF, we have hereunto set our hands this {{ $ordinalDay }} day of {{ $issuedMonth }} {{ $issuedYear }} at {{ $city }}, {{ $province }}, Philippines.</p>
    <br>
    <table class="signatures-table">
      <tr>
        <td class="sig-cell">
            <div class="sig-name">{{ strtoupper($groomName) }}</div>
            <span class="sig-affiant">Affiant</span>
            <span class="sig-line">{{ $groomIdType ?: '________________' }} {{ $groomIdNumber ?: '' }}</span>
            <span class="sig-line">Issued on {{ $groomIssuedOn ?: '____________' }}</span>
            <span class="sig-line">Issued at {{ $groomIssuedAt ?: '____________' }}</span>
        </td>
        <td class="sig-cell">
          <div class="sig-name">{{ strtoupper($brideName) }}</div>
          <span class="sig-affiant">Affiant</span>
          <span class="sig-line">{{ $brideIdType ?: '________________' }} {{ $brideIdNumber ?: '' }}</span>
          <span class="sig-line">Issued on {{ $brideIssuedOn ?: '____________' }}</span>
          <span class="sig-line">Issued at {{ $brideIssuedAt ?: '____________' }}</span>
        </td>
      </tr>
    </table>
    <p class="small" style="margin-top: 30px; text-indent: 40px;">SUBSCRIBED AND SWORN TO before me this ____ day of ________________ at Abuyog, Leyte, Philippines, affiants having exhibited to me their competent proof of their true identities as indicated below their respective
    names.</p>
  </div>
</body>
</html>
