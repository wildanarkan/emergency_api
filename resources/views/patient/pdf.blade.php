<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Patient Record - {{ $patient->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 2px solid #333;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .content {
            width: 100%;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 25%;
            background-color: #f5f5f5;
        }

        .value {
            width: 75%;
        }

        .photo-container {
            text-align: center;
            margin: 10px 0;
        }

        .injury-photo {
            max-width: 300px;
            max-height: 200px;
            margin: 0 auto;
        }

        .photo-label {
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PATIENT MEDICAL RECORD</h2>
        <p>Generated on: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <td class="label">Name</td>
                <td class="value">{{ $patient->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Age</td>
                <td class="value">{{ $patient->age }} years old</td>
            </tr>
            <tr>
                <td class="label">Gender</td>
                <td class="value">{{ $patient->gender_text }}</td>
            </tr>
            <tr>
                <td class="label">Case Type</td>
                <td class="value">{{ $patient->case_text }}</td>
            </tr>
            <tr>
                <td class="label">Time of Incident</td>
                <td class="value">{{ $patient->formatted_time_incident }}</td>
            </tr>
            <tr>
                <td class="label">Mechanism</td>
                <td class="value">{{ $patient->mechanism ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Injury</td>
                <td class="value">{{ $patient->injury ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Photo Injury</td>
                <td class="value">
                    @if ($patient->has_photo_injury && file_exists(public_path($patient->photo_injury)))
                        <img src="{{ public_path($patient->photo_injury) }}" class="injury-photo">
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Symptoms</td>
                <td class="value">{{ $patient->symptom ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Treatment</td>
                <td class="value">{{ $patient->treatment ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Arrival Time</td>
                <td class="value">{{ $patient->arrival ? $patient->arrival->format('d-m-Y H:i:s') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hospital</td>
                <td class="value">{{ $patient->hospital->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Request</td>
                <td class="value">{{ $patient->request ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">{{ $patient->status_text }}</td>
            </tr>
            <tr>
                <td class="label">Nurse</td>
                <td class="value">{{ $patient->user->name ?? '-' }}</td>
            </tr>
        </table>


    </div>

    <div class="footer">
        This is a computer-generated document. No signature is required.
    </div>
</body>

</html>
