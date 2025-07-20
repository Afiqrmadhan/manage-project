<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAT Report of {{ $project['Title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .feature-table th {
            background-color: #C5E0B3;
            text-align: center;
        }

        .header-table {
            border: none;
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table td {
            border: none;
            padding: 0;
        }

        .header-table td img {
            display: block;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <table class="header-table" align="center">
        <tr>
            <td rowspan="2">
                @php
                $path = public_path('img/logo.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                @endphp
                <img src="{{ $base64 }}" alt="desnet logo" style="width: 200px; height: auto;">
            </td>
            <td class="header-title">FORM USER ACCEPTANCE TESTING (UAT)</td>
        </tr>
        <tr>
            <td class="header-title">{{ $project['Title'] }}</td>
        </tr>
    </table>

    <table class="feature-table" style="margin-top: 50px;">
        <tr>
            <th>No</th>
            <th>Feature</th>
            <th>UAT Date</th>
            <th>Validation Status</th>
            <th>Client Feedback</th>
            <th>Revision Notes</th>
        </tr>
        @php $no = 1; @endphp
        @foreach ($features as $feature)
            <tr>
                <td>{{ $no++ }}.</td>
                <td>{{ $feature['Feature'] }}</td>
                <td>{{ $feature['UATDate'] }}</td>
                <td>{{ $feature['ValidationStatus'] }}</td>
                <td>{{ $feature['ClientFeedbackStatus'] }}</td>
                <td>{{ $feature['RevisionNotes'] }}</td>
            </tr>
        @endforeach
    </table>

    <div style="margin-top: 20px; text-align: right;">
        Semarang, {{ date("d F Y") }}
    </div>

    <table style="margin-top: 20px;">
        <tr>
            <th colspan="2">PT. DES Teknologi Informasi</th>
            <th colspan="2">{{ $project['ClientCompany'] }}</th>
        </tr>
        <tr>
            <th>Name</th>
            <th>Signature</th>
            <th>Name</th>
            <th>Signature</th>
        </tr>
        <tr>
            <td style="height: 100px;">{{ $project['ProjectManager'] }}</td>
            <td></td>
            <td style="height: 100px;">{{ $project['ClientName'] }}</td>
            <td></td>
        </tr>
    </table>


</body>

</html>