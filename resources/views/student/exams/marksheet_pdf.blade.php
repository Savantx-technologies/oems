<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Academic Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 20px;
            background: #fffef8;
        }

        .container {
            width: 100%;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
        }

        .sub-text {
            font-size: 14px;
        }

        .class-title {
            font-weight: bold;
            margin-top: 5px;
        }

        .logo {
            width: 120px;
        }

        .student-photo {
            width: 120px;
            height: 140px;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
        }

        .info-table td {
            padding: 4px;
        }

        /* MAIN RESULT TABLE */

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .main-table th {
            background: #cfd6e0;
        }

        .subject-col {
            text-align: left;
            font-weight: bold;
        }

        .highlight-row {
            background: #b39e8f;
            font-weight: bold;
        }

        .co-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .co-table th,
        .co-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .co-table th {
            background: #cfd6e0;
        }

        .black-strip {
            background: #000;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            font-size: 15px;
        }

        .sign-table {
            width: 100%;
            margin-top: 20px;
        }

        .sign-table td {
            text-align: center;
            padding-top: 40px;
        }

        .instructions {
            margin-top: 20px;
            font-size: 12px;
        }

        .scale-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .scale-table th,
        .scale-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .scale-table th {
            background: #cfd6e0;
        }
    </style>
</head>

<body>

    @php
    $first = $allAttempts->first();
    $totalMax = 0;
    $totalObtained = 0;
    @endphp

    <div class="container">

        <!-- ================= HEADER ================= -->
        <table width="100%">
            <tr>
                <td width="20%">
                    <img src="{{ public_path('storage/'.$school->logo) }}" class="logo">
                </td>

                <td width="60%" class="header">
                    <div class="school-name">{{ $school->name }}</div>
                    <div class="sub-text">
                        Affiliated To : CBSE Board / Affiliation No : {{ $school->code }}
                    </div>
                    <div class="sub-text">
                        Ph {{ $student->phone_number ?? '' }} , Email : {{ $student->email ?? '' }}
                    </div>
                    <div class="sub-text">
                        Academic Report
                    </div>
                    <div class="class-title">
                        Academic Session : {{ $first->exam->academic_session }} <br>
                        Class : {{ $student->grade }}
                    </div>
                </td>

                <td width="20%" align="right">
                    <img src="{{ public_path('storage/'.$student->photo) }}" class="student-photo">
                </td>
            </tr>
        </table>

        <!-- ================= STUDENT DETAILS ================= -->
        <table class="info-table">
            <tr>
                <td><strong>Name of Student :</strong> {{ $student->name }}</td>
                <td><strong>Roll No :</strong> {{ $student->roll_no }}</td>
            </tr>
            <tr>
                <td><strong>Father's Name :</strong> {{ $student->father_name ?? '' }}</td>
                <td><strong>Admission No :</strong> {{ $student->admission_number }}</td>
            </tr>
            <tr>
                <td><strong>Mother's Name :</strong> {{ $student->mother_name ?? '' }}</td>
                <td><strong>Date of Birth :</strong> {{ \Carbon\Carbon::parse($student->dob)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Address :</strong> {{ $student->address }}</td>
            </tr>
        </table>

        <!-- ================= MAIN RESULT TABLE ================= -->
        <table class="main-table">

            <tr>
                <th>Subject</th>
                <th>Max Marks</th>
                <th>Marks Obtained</th>
                <th>Grade</th>
            </tr>

            @foreach($allAttempts as $attempt)

            @php
            $max = $attempt->exam->total_marks;
            $score = $attempt->score;

            $totalMax += $max;
            $totalObtained += $score;

            $percentage = $max > 0 ? ($score/$max)*100 : 0;

            if ($percentage >= 91) $grade = 'A+';
            elseif ($percentage >= 81) $grade = 'A';
            elseif ($percentage >= 71) $grade = 'B+';
            elseif ($percentage >= 61) $grade = 'B';
            elseif ($percentage >= 51) $grade = 'C+';
            elseif ($percentage >= 41) $grade = 'C';
            elseif ($percentage >= 33) $grade = 'D';
            else $grade = 'E';
            @endphp

            <tr>
                <td class="subject-col">{{ $attempt->exam->subject }}</td>
                <td>{{ $max }}</td>
                <td>{{ $score }}</td>
                <td>{{ $grade }}</td>
            </tr>

            @endforeach

            @php
            $overallPercentage = $totalMax > 0
            ? round(($totalObtained/$totalMax)*100,2)
            : 0;

            // Overall Grade Calculation
            if ($overallPercentage >= 91) $overallGrade = 'A+';
            elseif ($overallPercentage >= 81) $overallGrade = 'A';
            elseif ($overallPercentage >= 71) $overallGrade = 'B+';
            elseif ($overallPercentage >= 61) $overallGrade = 'B';
            elseif ($overallPercentage >= 51) $overallGrade = 'C+';
            elseif ($overallPercentage >= 41) $overallGrade = 'C';
            elseif ($overallPercentage >= 33) $overallGrade = 'D';
            else $overallGrade = 'E';

            // Final Result Status
            $finalStatus = $overallPercentage >= 33 ? 'PASS' : 'FAIL';
            @endphp

            <tr class="highlight-row">
                <td>Total Marks</td>
                <td>{{ $totalMax }}</td>
                <td>{{ $totalObtained }}</td>
                <td>{{ $overallPercentage }}%</td>
            </tr>
            <tr class="highlight-row">
                <td class="border px-4 py-3 font-bold">Result</td>
                <td class="border px-4 py-3 text-center">
                    {{ $overallPercentage }} %
                </td>
                <td class="border px-4 py-3 text-center font-bold">
                    Grade : {{ $overallGrade }}
                </td>
                <td class="border px-4 py-3 text-center font-bold 
                    {{ $finalStatus == 'PASS' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $finalStatus }}
                </td>
            </tr>
        </table>
        <hr>
        <!-- ================= INSTRUCTIONS ================= -->
        <div class="instructions">
            <strong>Grading scale for scholastic areas:</strong>
        </div>

        <table class="scale-table">
            <tr>
                <th>Marks Range in (%)</th>
                <th>91-100</th>
                <th>81-90</th>
                <th>71-80</th>
                <th>61-70</th>
                <th>51-60</th>
                <th>41-50</th>
                <th>33-40</th>
            </tr>
            <tr>
                <td><strong>Grade</strong></td>
                <td>A+</td>
                <td>A</td>
                <td>B+</td>
                <td>B</td>
                <td>C+</td>
                <td>C</td>
                <td>D</td>
            </tr>
        </table>

    </div>
</body>

</html>