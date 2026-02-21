<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Domestic Data Entry Operator - Training Delivery Plan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
            box-sizing: border-box;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f4f8;
            color: #24323d;
        }

        /* Layout */
        .page-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #194b73 0%, #2c3e50 100%);
            color: #fff;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.3em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 15px;
            color: #fff;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 15px;
            display: block;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateX(5px);
        }

        /* Main Content Area */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-x: hidden;
        }

        /* Module Section (Card style) */
        .module-section {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            scroll-margin-top: 20px;
        }

        /* Typography & Elements */
        h1,
        h2,
        h3 {
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        h1 {
            font-weight: 700;
            font-size: 2.2em;
            color: #194b73;
        }

        h2 {
            margin-top: 38px;
            font-size: 1.4em;
            color: #2268a0;
        }

        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 4px;
        }

        .header-bar img {
            height: 84px;
            width: auto;
            max-width: 165px;
            border-radius: 6px;
            background: #e7eef7;
            padding: 4px;
        }

        .subtitle {
            text-align: center;
            font-weight: bold;
            color: #3d80b7;
            margin-bottom: 17px;
        }

        .info-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px 40px;
            background: #f3f9fd;
            border-radius: 10px;
            padding: 14px 24px;
            margin: 0 auto 25px auto;
            max-width: 800px;
        }

        .highlight {
            font-weight: bold;
            color: #205c91;
        }

        /* Table Styles */
        .schedule-table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 30px;
            font-size: 15px;
            background-color: #f9fbfc;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #d5e1f2;
        }

        th {
            background: linear-gradient(90deg, #2980b9 0%, #6dd5fa 100%);
            color: #fff;
            padding: 12px 7px;
            border-bottom: 3px solid #156089;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #d5e1f2;
            background: #fff;
            vertical-align: top;
        }

        .day-section td {
            background: linear-gradient(90deg, #e9f2ff 80%, #d7eafd 100%);
            font-weight: bold;
            color: #185176;
            border-left: 5px solid #2980b9;
        }

        .day-break {
            height: 14px;
            background: none;
            border: none !important;
        }

        footer {
            margin-top: 40px;
            padding: 20px;
            background: linear-gradient(90deg, #2268a0 80%, #2c3e50 100%);
            color: #fff;
            text-align: center;
            border-radius: 12px;
        }

        footer a {
            color: #ffffdf;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .page-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: sticky;
                top: 0;
                padding: 10px 15px;
                display: flex;
                align-items: center;
                overflow-x: auto;
                white-space: nowrap;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            }

            .sidebar h3 {
                margin: 0 20px 0 0;
                padding: 0;
                border: none;
                font-size: 1.1em;
                display: inline-block;
            }

            .sidebar ul {
                display: flex;
                flex-direction: row;
                gap: 10px;
            }

            .sidebar li {
                margin: 0;
            }

            .sidebar a {
                padding: 8px 16px;
                background: rgba(255, 255, 255, 0.15);
                font-size: 0.9em;
            }

            .sidebar a:hover {
                transform: none;
                background: rgba(255, 255, 255, 0.25);
            }

            .content-area {
                padding: 15px;
            }

            .module-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .header-bar {
                flex-direction: column;
            }

            .header-bar h1 {
                font-size: 1.5em;
            }
        }

        @media (max-width: 600px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #d5e1f2;
                border-radius: 8px;
                overflow: hidden;
            }

            td {
                padding: 10px;
                position: relative;
                padding-left: 50%;
                text-align: right;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #205c91;
                text-align: left;
                flex: 1;
            }

            .day-section td {
                padding-left: 15px;
                text-align: left;
                display: block;
            }

            .day-break {
                display: none;
            }
        }

        .header-title {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-title h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.pdf-btn {
    background-color: #dc2626;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s ease;
}

.pdf-btn:hover {
    background-color: #b91c1c;
}
    </style>
</head>

<body>

    <div class="page-layout">

        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <h3>Training Modules</h3>
            <ul>
                <li><a href="#module1">Module 1</a></li>
                <li><a href="#module2">Module 2</a></li>
                <li><a href="#module3">Module 3</a></li>
                <li><a href="#module4">Module 4</a></li>
                <li><a href="#module5">Module 5</a></li>
                <li><a href="#module6">Module 6</a></li>
                <li><a href="#module7">Module 7</a></li>
                <li><a href="#module8">Module 8</a></li>
                <li><a href="#module9">Module 9</a></li>
                <li><a href="#module10">Module 10</a></li>
                <li><a href="#module11">Module 11</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content-area">

            <!-- Module 1 -->
            <div id="module1" class="module-section">
                <div class="header-bar">
                    <span class="header-logo">
                        <img src="{{ asset('storage/Logo.png') }}" alt="Left Logo">
                    </span>

                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Courier Executive - Operations</h1>

                        <a href="{{ asset('pdf/module1.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>

                    <span class="header-logo">
                        <img src="{{ asset('storage/school-logos/NSDC-Preview.png') }}" alt="Right Logo"
                            onerror="this.onerror=null;this.src='{{ asset('NSDC-Preview.png') }}';">
                    </span>
                </div>
                <div class="subtitle">Certificate Program - Training Delivery Plan</div>

                <div class="info-box">
                    <p><span class="highlight">Qualification Pack:</span>LSC/Q1902</p>
                    <p><span class="highlight">Version:</span> 2.0</p>
                    <p><span class="highlight">Sector:</span> Logistics</p>
                    <p><span class="highlight">Sub-Sector:</span> Courier and Express Services</p>
                    <p><span class="highlight">Occupation:</span> Hub/ Branch Operations, Institutional Sales, Branch
                        Sales,
                        Customer Relationship Management</p>
                </div>

                <h2>Training Outcome</h2>
                <p style="text-align:center; font-size:1.08em; max-width:680px; margin:0 auto 22px auto;">
                    By the end of this program, the participant will be able to maintain required customer data
                    efficiently
                    using various data entry software, tools, and techniques.
                </p>

                <h1>Introduction to Courier Executive - Operations</h1>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Day 1 -->
                            <tr class="day-section">
                                <td colspan="8">Day 1</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">1</td>
                                <td data-label="Module Name">Introduction to Courier Executive – Operations</td>
                                <td data-label="Session Name">Icebreaker</td>
                                <td data-label="Session Objectives">Recognize and build rapport with participants and
                                    trainer</td>
                                <td data-label="Methodology">Interactive discussion</td>
                                <td data-label="NOS Reference">Bridge Module</td>
                                <td data-label="Training Tools/Aids">Trainer Guide, Participant Handbook</td>
                                <td data-label="Duration">1 Hour</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">2</td>
                                <td data-label="Module Name">Supply Chain & Logistics Overview</td>
                                <td data-label="Session Name">Components of Supply Chain</td>
                                <td data-label="Session Objectives">Explain supply chain components and logistics
                                    functions</td>
                                <td data-label="Methodology">Presentation + Group Discussion</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">PPT, Whiteboard, Case Study</td>
                                <td data-label="Duration">2 Hours</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">3</td>
                                <td data-label="Module Name">Logistics Sub-Sectors</td>
                                <td data-label="Session Name">Sub-sectors & Opportunities</td>
                                <td data-label="Session Objectives">Describe courier, freight, warehousing and 3PL
                                    sectors and job opportunities</td>
                                <td data-label="Methodology">Lecture + Industry examples</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">PPT, Videos</td>
                                <td data-label="Duration">3 Hours</td>
                            </tr>

                            <!-- Day 2 -->
                            <tr class="day-break"></tr>
                            <tr class="day-section">
                                <td colspan="8">Day 2</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">4</td>
                                <td data-label="Module Name">Job Roles in Courier/Express</td>
                                <td data-label="Session Name">Roles & Responsibilities</td>
                                <td data-label="Session Objectives">Explain various job roles in courier and express
                                    operations</td>
                                <td data-label="Methodology">Trainer led discussion</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">Role charts, Handbook</td>
                                <td data-label="Duration">3 Hours</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">5</td>
                                <td data-label="Module Name">Courier Executive – Operations</td>
                                <td data-label="Session Name">Detailed Job Role</td>
                                <td data-label="Session Objectives">Describe responsibilities and coordination with
                                    other roles</td>
                                <td data-label="Methodology">Case study + Discussion</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">Process flow chart</td>
                                <td data-label="Duration">3 Hours</td>
                            </tr>

                            <!-- Day 3 -->
                            <tr class="day-break"></tr>
                            <tr class="day-section">
                                <td colspan="8">Day 3</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">6</td>
                                <td data-label="Module Name">Material Handling Equipment (MHE)</td>
                                <td data-label="Session Name">Types of Equipment</td>
                                <td data-label="Session Objectives">Identify and use MHE safely in courier operations
                                </td>
                                <td data-label="Methodology">Demonstration + Practical</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">Pallet truck, Scanner, PPE kit</td>
                                <td data-label="Duration">6 Hours</td>
                            </tr>

                            <!-- Day 4 -->
                            <tr class="day-break"></tr>
                            <tr class="day-section">
                                <td colspan="8">Day 4</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">7</td>
                                <td data-label="Module Name">Documentation in Courier Operations</td>
                                <td data-label="Session Name">Operational Documentation</td>
                                <td data-label="Session Objectives">Understand AWB, POD, Invoice, E-way bill
                                    documentation requirements</td>
                                <td data-label="Methodology">Presentation + Sample documents review</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">Sample AWB, Invoice copies</td>
                                <td data-label="Duration">6 Hours</td>
                            </tr>

                            <!-- Day 5 -->
                            <tr class="day-break"></tr>
                            <tr class="day-section">
                                <td colspan="8">Day 5</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">8</td>
                                <td data-label="Module Name">Safety & Compliance</td>
                                <td data-label="Session Name">Workplace Safety Practices</td>
                                <td data-label="Session Objectives">Follow safety standards and compliance requirements
                                </td>
                                <td data-label="Methodology">Video + Demonstration</td>
                                <td data-label="NOS Reference">LSC/N1907</td>
                                <td data-label="Training Tools/Aids">Safety manual, PPE</td>
                                <td data-label="Duration">6 Hours</td>
                            </tr>

                            <!-- Day 6 -->
                            <tr class="day-break"></tr>
                            <tr class="day-section">
                                <td colspan="8">Day 6</td>
                            </tr>
                            <tr>
                                <td data-label="Sr. No.">9</td>
                                <td data-label="Module Name">Assessment & Feedback</td>
                                <td data-label="Session Name">Module Assessment</td>
                                <td data-label="Session Objectives">Evaluate learning outcomes and clarify doubts</td>
                                <td data-label="Methodology">Written Test + Discussion</td>
                                <td data-label="NOS Reference">Assessment</td>
                                <td data-label="Training Tools/Aids">Question Paper, Feedback Form</td>
                                <td data-label="Duration">6 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Module 2 -->
            <div id="module2" class="module-section">

                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>ERP Data Analysis in Courier Hub</h1>

                        <a href="{{ asset('pdf/module2.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 60 Hours (Day 7 – Day 17 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Sub Topic</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration (Hours)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 7 </td>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>List of data for shipment analysis (AWB, weight, destination, status)</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for loading activities</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 8 </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for unloading activities</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for packing activities</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 9 </td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for binning and sorting activities</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for priority shipments</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 10 </td>
                            </tr>

                            <tr>
                                <td>7</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Data collection for complaints and delays</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Escalation management data recording</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 11 </td>
                            </tr>

                            <tr>
                                <td>9</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Customer feedback data handling</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Uploading floor operations data in ERP</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 12 </td>
                            </tr>

                            <tr>
                                <td>11</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Uploading priority and complaint data in ERP</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>12</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Inventory count and reconciliation data entry</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 13 </td>
                            </tr>

                            <tr>
                                <td>13</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Error identification and correction in ERP</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>14</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Importance of trend analysis in courier hub</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 14 </td>
                            </tr>

                            <tr>
                                <td>15</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Analyzing shipment and delay trends</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>16</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Using trend reports for operational improvement</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 15 </td>
                            </tr>

                            <tr>
                                <td>17</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Coordination with operations department</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr>
                                <td>18</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Coordination with customer support department</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 16 </td>
                            </tr>

                            <tr>
                                <td>19</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Query resolution process using ERP data</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>
                            <tr class="day-section">
                                <td colspan="8">Day 17 </td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>ERP Data Analysis in Courier Hub</td>
                                <td>Final review and assessment</td>
                                <td>Understand process, data requirements and system entry</td>
                                <td>Lecture + Demonstration + Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>PPT, ERP System, Case Studies, Reports</td>
                                <td>3</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 3 Placeholder -->
            <div id="module3" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Institutional Business Development</h1>

                        <a href="{{ asset('pdf/module3.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 66 Hours (Day 18 – Day 28 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 18 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Institutional Business Development</td>
                                <td>Introduction to Institutional Business</td>
                                <td>Understand basics of institutional business models</td>
                                <td>Lecture + Discussion</td>
                                <td>LSC/N1901</td>
                                <td>PPT, Whiteboard</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Institutional Business Development</td>
                                <td>Overview of Sales Cycle</td>
                                <td>Learn stages of institutional sales cycle</td>
                                <td>Flowchart explanation</td>
                                <td>LSC/N1901</td>
                                <td>Charts, Handouts</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Institutional Business Development</td>
                                <td>Role of ERP in Sales</td>
                                <td>Understand ERP utility in sales management</td>
                                <td>Demonstration</td>
                                <td>LSC/N1901</td>
                                <td>ERP Demo Environment</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 19 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Institutional Business Development</td>
                                <td>Sales Reports in ERP</td>
                                <td>Generate and interpret sales reports</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1901</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Institutional Business Development</td>
                                <td>Revenue & Volume Analysis</td>
                                <td>Analyze revenue streams and volume data</td>
                                <td>Case Study</td>
                                <td>LSC/N1901</td>
                                <td>Sample Data Sets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 20 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Institutional Business Development</td>
                                <td>Target vs Achievement Analysis</td>
                                <td>Compare sales targets with actual achievements</td>
                                <td>Workshop</td>
                                <td>LSC/N1901</td>
                                <td>Excel / ERP</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Institutional Business Development</td>
                                <td>Practical ERP Sales Report Preparation</td>
                                <td>Create customized sales reports in ERP</td>
                                <td>Lab Session</td>
                                <td>LSC/N1901</td>
                                <td>Computer Lab</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 21 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Institutional Business Development</td>
                                <td>Lead Generation Concepts</td>
                                <td>Understand methods for generating leads</td>
                                <td>Brainstorming</td>
                                <td>LSC/N1901</td>
                                <td>Flipchart</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Institutional Business Development</td>
                                <td>Market Research & Data Collection</td>
                                <td>Collect market data to identify prospects</td>
                                <td>Research Activity</td>
                                <td>LSC/N1901</td>
                                <td>Internet, Forms</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 22 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Institutional Business Development</td>
                                <td>Lead Entry & Tracking in ERP</td>
                                <td>Enter and track sales leads in ERP</td>
                                <td>Practical</td>
                                <td>LSC/N1901</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Institutional Business Development</td>
                                <td>Lead Qualification & Follow-up Strategy</td>
                                <td>Qualify leads and plan follow-ups</td>
                                <td>Role Play</td>
                                <td>LSC/N1901</td>
                                <td>Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 23 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Institutional Business Development</td>
                                <td>Prospecting Institutional Clients</td>
                                <td>Identify potential institutional clients</td>
                                <td>Group Activity</td>
                                <td>LSC/N1901</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Institutional Business Development</td>
                                <td>Preparing Sales Proposal</td>
                                <td>Draft effective sales proposals</td>
                                <td>Drafting Exercise</td>
                                <td>LSC/N1901</td>
                                <td>Templates</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 24 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Institutional Business Development</td>
                                <td>Negotiation Skills</td>
                                <td>Learn effective negotiation techniques</td>
                                <td>Video + Discussion</td>
                                <td>LSC/N1901</td>
                                <td>Video Clips</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Institutional Business Development</td>
                                <td>Handling Objections & Closing</td>
                                <td>Handle client objections and close deals</td>
                                <td>Simulation</td>
                                <td>LSC/N1901</td>
                                <td>Role Cards</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 25 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Institutional Business Development</td>
                                <td>Pricing Strategy & Margin Understanding</td>
                                <td>Understand pricing models and margins</td>
                                <td>Lecture + Calculation</td>
                                <td>LSC/N1901</td>
                                <td>Calculator, PPT</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Institutional Business Development</td>
                                <td>Role Play – Sales Negotiation</td>
                                <td>Practice negotiation in sales scenarios</td>
                                <td>Role Play</td>
                                <td>LSC/N1901</td>
                                <td>Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 26 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Institutional Business Development</td>
                                <td>Payment Collection Process</td>
                                <td>Understand payment collection workflow</td>
                                <td>Flowchart</td>
                                <td>LSC/N1901</td>
                                <td>Process Map</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Institutional Business Development</td>
                                <td>ERP Reports for Outstanding & Aging</td>
                                <td>Generate outstanding payment reports</td>
                                <td>Practical</td>
                                <td>LSC/N1901</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 27 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Institutional Business Development</td>
                                <td>Customer Retention Techniques</td>
                                <td>Learn strategies to retain customers</td>
                                <td>Discussion</td>
                                <td>LSC/N1901</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Institutional Business Development</td>
                                <td>Complaint & Feedback Analysis</td>
                                <td>Analyze feedback for improvement</td>
                                <td>Analysis Exercise</td>
                                <td>LSC/N1901</td>
                                <td>Feedback Forms</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 28 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Institutional Business Development</td>
                                <td>ERP for Sales Improvement</td>
                                <td>Use ERP data to improve sales</td>
                                <td>Presentation</td>
                                <td>LSC/N1901</td>
                                <td>PPT</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Institutional Business Development</td>
                                <td>Case Study & Group Discussion</td>
                                <td>Discuss real-world sales scenarios</td>
                                <td>Group Discussion</td>
                                <td>LSC/N1901</td>
                                <td>Case Study</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Institutional Business Development</td>
                                <td>Assessment & Review</td>
                                <td>Assess learning and review module</td>
                                <td>Test</td>
                                <td>Assessment</td>
                                <td>Question Paper</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 4 Placeholder -->
            <div id="module4" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Branch Sales</h1>

                        <a href="{{ asset('pdf/module4.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 66 Hours (Day 29 – Day 39 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 29 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Branch Sales</td>
                                <td>Introduction to Branch Sales</td>
                                <td>Understand the role and importance of branch sales in the courier industry.</td>
                                <td>Lecture + Q&A</td>
                                <td>LSC/N1903</td>
                                <td>PPT, Whiteboard</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Branch Sales</td>
                                <td>Grooming & Customer Etiquette</td>
                                <td>Learn professional grooming standards and customer interaction etiquette.</td>
                                <td>Presentation, Role Play</td>
                                <td>LSC/N0021</td>
                                <td>Dress code examples, Scenarios</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Branch Sales</td>
                                <td>Role Play – Customer Interaction</td>
                                <td>Practice handling customer greetings and initial interactions effectively.</td>
                                <td>Role Play</td>
                                <td>LSC/N0021</td>
                                <td>Role play cards</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 30 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Branch Sales</td>
                                <td>Understanding Customer Queries</td>
                                <td>Identify common customer queries and learn how to address them accurately.</td>
                                <td>Group Discussion, Case Studies</td>
                                <td>LSC/N1903</td>
                                <td>Sample queries, Handouts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Branch Sales</td>
                                <td>Sales Support through ERP</td>
                                <td>Utilize the ERP system to find information for providing sales support.</td>
                                <td>Practical Demo, Lab session</td>
                                <td>LSC/N1901</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 31 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Branch Sales</td>
                                <td>Identifying Customer Needs</td>
                                <td>Learn techniques to identify both explicit and implicit customer needs.</td>
                                <td>Workshop, Questioning techniques</td>
                                <td>LSC/N1903</td>
                                <td>Worksheets, Scenarios</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Branch Sales</td>
                                <td>Matching Services to Needs</td>
                                <td>Match appropriate courier services to specific customer requirements.</td>
                                <td>Case Study, Group Activity</td>
                                <td>LSC/N1903</td>
                                <td>Service catalog, Case studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 32 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Branch Sales</td>
                                <td>Product Features & Benefits</td>
                                <td>Explain the features and benefits of different courier products to customers.</td>
                                <td>Presentation, Q&A</td>
                                <td>LSC/N1903</td>
                                <td>Product brochures, PPT</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Branch Sales</td>
                                <td>Value Added Services</td>
                                <td>Understand and upsell value-added services like insurance and COD.</td>
                                <td>Lecture, Role Play</td>
                                <td>LSC/N1903</td>
                                <td>Service list, Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 33 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Branch Sales</td>
                                <td>Timeline & Delivery Tracking</td>
                                <td>Explain delivery timelines and demonstrate how to track shipments for customers.
                                </td>
                                <td>Flowchart explanation, Discussion</td>
                                <td>LSC/N1904</td>
                                <td>Tracking website, Process maps</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Branch Sales</td>
                                <td>ERP Tracking Practical</td>
                                <td>Practice tracking shipments for various scenarios using the ERP system.</td>
                                <td>Practical Lab Session</td>
                                <td>LSC/N1904</td>
                                <td>ERP System, Sample AWBs</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 34 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Branch Sales</td>
                                <td>Complaint Handling Process</td>
                                <td>Understand the standard operating procedure for handling customer complaints.</td>
                                <td>Process walkthrough, Lecture</td>
                                <td>LSC/N0022</td>
                                <td>Flowchart, Complaint forms</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Branch Sales</td>
                                <td>Escalation & Feedback Management</td>
                                <td>Learn when and how to escalate issues and properly manage customer feedback.</td>
                                <td>Case Study, Discussion</td>
                                <td>LSC/N0022</td>
                                <td>Escalation matrix, Feedback forms</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 35 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Branch Sales</td>
                                <td>Communication Skills for Resolution</td>
                                <td>Develop effective communication skills for de-escalation and conflict resolution.
                                </td>
                                <td>Role Play, Video analysis</td>
                                <td>LSC/N0021</td>
                                <td>Scenarios, Video clips</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Branch Sales</td>
                                <td>Case Studies</td>
                                <td>Analyze real-world complaint scenarios and collaboratively find solutions.</td>
                                <td>Group work, Presentation</td>
                                <td>LSC/N0022</td>
                                <td>Case study handouts</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 36 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Branch Sales</td>
                                <td>Daily Sales Report Preparation</td>
                                <td>Learn to prepare and analyze daily sales reports for performance tracking.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1901</td>
                                <td>Report templates, Excel/ERP</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Branch Sales</td>
                                <td>Documentation Process</td>
                                <td>Understand the complete documentation required for branch sales operations.</td>
                                <td>Lecture, Document review</td>
                                <td>LSC/N1902</td>
                                <td>Sample documents</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 37 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Branch Sales</td>
                                <td>Cash Handling Procedures</td>
                                <td>Learn safe, secure, and accurate cash handling procedures.</td>
                                <td>Demonstration, Lecture</td>
                                <td>LSC/N1902</td>
                                <td>Cash box, POS machine</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Branch Sales</td>
                                <td>Cash Reconciliation Practical</td>
                                <td>Practice reconciling cash collections at the end of the day.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1902</td>
                                <td>Reconciliation sheets, Calculator</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 38 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Branch Sales</td>
                                <td>Risk Management in Branch Sales</td>
                                <td>Identify and learn how to mitigate common risks in branch sales.</td>
                                <td>Brainstorming, Discussion</td>
                                <td>LSC/N0024</td>
                                <td>Risk matrix, Whiteboard</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Branch Sales</td>
                                <td>ERP Sales Reporting Practice</td>
                                <td>Gain additional practice in generating and interpreting ERP sales reports.</td>
                                <td>Lab Session</td>
                                <td>LSC/N1901</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 39 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Branch Sales</td>
                                <td>Integrated Case Study</td>
                                <td>Apply all learned concepts in a comprehensive branch sales scenario.</td>
                                <td>Group Case Study</td>
                                <td>LSC/N1901, LSC/N1903</td>
                                <td>Detailed case study document</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Branch Sales</td>
                                <td>Assessment & Review</td>
                                <td>Assess module learning outcomes and review key topics.</td>
                                <td>Test, Q&A</td>
                                <td>Assessment</td>
                                <td>Question Paper, Feedback Form</td>
                                <td>3 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 5 Placeholder -->
            <div id="module5" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Shipment Classification & Customs Clearance</h1>

                        <a href="{{ asset('pdf/module5.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 66 Hours (Day 40 – Day 50 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 40 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Customs Clearance</td>
                                <td>Introduction to Customs & International Shipments</td>
                                <td>Understand the basics of customs and international shipping.</td>
                                <td>Lecture + PPT</td>
                                <td>LSC/N1905</td>
                                <td>PPT, Whiteboard</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Customs Clearance</td>
                                <td>Overview of Pre-Clearance Process</td>
                                <td>Learn the steps involved in the pre-clearance of shipments.</td>
                                <td>Flowchart Discussion</td>
                                <td>LSC/N1905</td>
                                <td>Process Charts</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Customs Clearance</td>
                                <td>Compliance Importance</td>
                                <td>Understand the importance of regulatory compliance in logistics.</td>
                                <td>Case Study</td>
                                <td>LSC/N1905</td>
                                <td>Case Studies</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 41 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Customs Clearance</td>
                                <td>Pre-Clearance Checkpoints</td>
                                <td>Identify key checkpoints for pre-clearance verification.</td>
                                <td>Workshop</td>
                                <td>LSC/N1905</td>
                                <td>Checklists</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Customs Clearance</td>
                                <td>Practical Document Verification</td>
                                <td>Practice verifying documents for accuracy and completeness.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Sample Documents</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 42 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Customs Clearance</td>
                                <td>Customs Documentation – Export</td>
                                <td>Prepare and review documentation required for exports.</td>
                                <td>Lecture + Demo</td>
                                <td>LSC/N1905</td>
                                <td>Export Forms</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Customs Clearance</td>
                                <td>Customs Documentation – Import</td>
                                <td>Prepare and review documentation required for imports.</td>
                                <td>Lecture + Demo</td>
                                <td>LSC/N1905</td>
                                <td>Import Forms</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 43 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Customs Clearance</td>
                                <td>HSN Code Structure</td>
                                <td>Understand the Harmonized System of Nomenclature (HSN).</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>HSN Directory</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Customs Clearance</td>
                                <td>Practical HSN Identification Exercise</td>
                                <td>Practice identifying correct HSN codes for various products.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Product List</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 44 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Customs Clearance</td>
                                <td>Duties & Taxes Structure</td>
                                <td>Learn about different types of customs duties and taxes.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Tax Charts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Customs Clearance</td>
                                <td>Duty Calculation Practical</td>
                                <td>Calculate duties and taxes for specific shipments.</td>
                                <td>Calculation Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Calculator, Worksheets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 45 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Customs Clearance</td>
                                <td>Bill of Entry & Shipping Bill Filing</td>
                                <td>Understand the process of filing Bill of Entry and Shipping Bills.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1905</td>
                                <td>Filing Software Demo</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Customs Clearance</td>
                                <td>ERP/System Entry Practice</td>
                                <td>Practice entering customs data into the ERP system.</td>
                                <td>Lab Session</td>
                                <td>LSC/N1905</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 46 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Customs Clearance</td>
                                <td>Product & Geography Compliance</td>
                                <td>Understand compliance requirements based on product and geography.</td>
                                <td>Lecture + Discussion</td>
                                <td>LSC/N1905</td>
                                <td>Compliance Manual</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Customs Clearance</td>
                                <td>Case Studies on Restricted Goods</td>
                                <td>Analyze cases involving restricted or prohibited goods.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N1905</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 47 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Customs Clearance</td>
                                <td>Packaging & Statutory Compliance</td>
                                <td>Ensure packaging meets statutory compliance standards.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1905</td>
                                <td>Packaging Materials</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Customs Clearance</td>
                                <td>Documentation Verification Workshop</td>
                                <td>Comprehensive workshop on verifying all clearance documents.</td>
                                <td>Workshop</td>
                                <td>LSC/N1905</td>
                                <td>Document Sets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 48 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Customs Clearance</td>
                                <td>Customs Broker Process</td>
                                <td>Understand the role and process of customs brokers.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Process Map</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Customs Clearance</td>
                                <td>Interaction Flow with Broker</td>
                                <td>Learn how to effectively interact and coordinate with brokers.</td>
                                <td>Role Play</td>
                                <td>LSC/N1905</td>
                                <td>Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 49 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Customs Clearance</td>
                                <td>Clearance Tracking & Query Handling</td>
                                <td>Track clearance status and handle queries from customs.</td>
                                <td>Simulation</td>
                                <td>LSC/N1905</td>
                                <td>Tracking Tools</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Customs Clearance</td>
                                <td>Real Case Simulation</td>
                                <td>Simulate a real-world customs clearance scenario.</td>
                                <td>Simulation</td>
                                <td>LSC/N1905</td>
                                <td>Simulation Kit</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 50 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Customs Clearance</td>
                                <td>Integrated Case Study</td>
                                <td>Apply all learned concepts in a comprehensive case study.</td>
                                <td>Group Activity</td>
                                <td>LSC/N1905</td>
                                <td>Case Study</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Customs Clearance</td>
                                <td>Practical Assessment</td>
                                <td>Assess practical skills in classification and clearance.</td>
                                <td>Practical Test</td>
                                <td>Assessment</td>
                                <td>Test Papers</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Customs Clearance</td>
                                <td>Module Review & Feedback</td>
                                <td>Review key topics and collect feedback.</td>
                                <td>Discussion</td>
                                <td>N/A</td>
                                <td>Feedback Forms</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 6 Placeholder -->
            <div id="module6" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Customer Service Management</h1>

                        <a href="{{ asset('pdf/module6.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 66 Hours (Day 51 – Day 61 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 51 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Customer Service</td>
                                <td>Introduction to Customer Service</td>
                                <td>Understand the fundamentals of customer service.</td>
                                <td>Lecture</td>
                                <td>LSC/N1906</td>
                                <td>PPT</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Customer Service</td>
                                <td>Importance of Communication Skills</td>
                                <td>Learn effective communication techniques.</td>
                                <td>Role Play</td>
                                <td>LSC/N1906</td>
                                <td>Handouts</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Customer Service</td>
                                <td>Telephone Etiquette Basics</td>
                                <td>Master professional telephone handling skills.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1906</td>
                                <td>Phone System</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 52 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Customer Service</td>
                                <td>Call Handling Practice</td>
                                <td>Practice handling inbound and outbound calls.</td>
                                <td>Practical</td>
                                <td>LSC/N1906</td>
                                <td>Mock Calls</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Customer Service</td>
                                <td>Difficult Customer Handling</td>
                                <td>Techniques to manage difficult customers.</td>
                                <td>Case Study</td>
                                <td>LSC/N1906</td>
                                <td>Scenarios</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Customer Service</td>
                                <td>Role Play Session</td>
                                <td>Simulate real-life customer service scenarios.</td>
                                <td>Role Play</td>
                                <td>LSC/N1906</td>
                                <td>Role Cards</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 53 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Customer Service</td>
                                <td>Email Writing Standards</td>
                                <td>Learn professional email etiquette and structure.</td>
                                <td>Lecture</td>
                                <td>LSC/N1906</td>
                                <td>PPT</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Customer Service</td>
                                <td>Drafting Practice</td>
                                <td>Practice drafting responses to customer emails.</td>
                                <td>Exercise</td>
                                <td>LSC/N1906</td>
                                <td>Worksheets</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Customer Service</td>
                                <td>Email Escalation Process</td>
                                <td>Understand when and how to escalate via email.</td>
                                <td>Flowchart</td>
                                <td>LSC/N1906</td>
                                <td>Process Map</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 54 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Customer Service</td>
                                <td>Types of Customer Queries</td>
                                <td>Identify and categorize different customer queries.</td>
                                <td>Discussion</td>
                                <td>LSC/N1906</td>
                                <td>Examples</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Customer Service</td>
                                <td>Case Study Discussion</td>
                                <td>Analyze complex customer service cases.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N1906</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 55 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Customer Service</td>
                                <td>ERP Ticket Creation</td>
                                <td>Learn to create support tickets in ERP.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1906</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Customer Service</td>
                                <td>Query Forwarding Process</td>
                                <td>Understand the workflow for forwarding queries.</td>
                                <td>Practical</td>
                                <td>LSC/N1906</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 56 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Customer Service</td>
                                <td>Escalation Matrix</td>
                                <td>Understand the hierarchy for issue escalation.</td>
                                <td>Lecture</td>
                                <td>LSC/N1906</td>
                                <td>Matrix Chart</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Customer Service</td>
                                <td>Interdepartmental Coordination</td>
                                <td>Coordinate with other departments for resolution.</td>
                                <td>Role Play</td>
                                <td>LSC/N1906</td>
                                <td>Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 57 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Customer Service</td>
                                <td>Documentation in Courier Processing</td>
                                <td>Review necessary documentation for claims/queries.</td>
                                <td>Lecture</td>
                                <td>LSC/N1906</td>
                                <td>Sample Docs</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Customer Service</td>
                                <td>Practical Form Filling</td>
                                <td>Practice filling out service forms correctly.</td>
                                <td>Exercise</td>
                                <td>LSC/N1906</td>
                                <td>Forms</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 58 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Customer Service</td>
                                <td>Complaint Investigation Process</td>
                                <td>Steps to investigate customer complaints.</td>
                                <td>Case Study</td>
                                <td>LSC/N1906</td>
                                <td>Investigation Report</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Customer Service</td>
                                <td>Case Analysis</td>
                                <td>Deep dive into specific complaint cases.</td>
                                <td>Group Work</td>
                                <td>LSC/N1906</td>
                                <td>Case Files</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 59 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Customer Service</td>
                                <td>ERP Query Tracking</td>
                                <td>Track the status of queries in ERP.</td>
                                <td>Practical</td>
                                <td>LSC/N1906</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Customer Service</td>
                                <td>TAT Monitoring</td>
                                <td>Monitor Turn Around Time for resolutions.</td>
                                <td>Analysis</td>
                                <td>LSC/N1906</td>
                                <td>Reports</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 60 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Customer Service</td>
                                <td>Query Closure in ERP</td>
                                <td>Properly close resolved queries in the system.</td>
                                <td>Practical</td>
                                <td>LSC/N1906</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Customer Service</td>
                                <td>Quality Check Process</td>
                                <td>Ensure resolution meets quality standards.</td>
                                <td>Checklist</td>
                                <td>LSC/N1906</td>
                                <td>QC Checklist</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 61 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Customer Service</td>
                                <td>Integrated Role Play</td>
                                <td>Comprehensive role play covering all topics.</td>
                                <td>Role Play</td>
                                <td>LSC/N1906</td>
                                <td>Scenarios</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>Customer Service</td>
                                <td>Practical Assessment</td>
                                <td>Assess practical customer service skills.</td>
                                <td>Assessment</td>
                                <td>Assessment</td>
                                <td>Evaluation Sheet</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>26</td>
                                <td>Customer Service</td>
                                <td>Module Review & Feedback</td>
                                <td>Review module learnings and feedback.</td>
                                <td>Discussion</td>
                                <td>N/A</td>
                                <td>Feedback Form</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 7 Placeholder -->
            <div id="module7" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Integrity & Ethics</h1>

                        <a href="{{ asset('pdf/module7.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 36 Hours (Day 62 – Day 67 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 62 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Integrity & Ethics</td>
                                <td>Introduction to Integrity & Ethics</td>
                                <td>Understand the fundamental concepts of integrity and ethics in professional
                                    settings.</td>
                                <td>Lecture + Discussion</td>
                                <td>LSC/N1907</td>
                                <td>PPT, Handouts</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Integrity & Ethics</td>
                                <td>Organizational Values</td>
                                <td>Learn about the core values of the organization and their importance.</td>
                                <td>Presentation</td>
                                <td>LSC/N1907</td>
                                <td>Company Values Document</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Integrity & Ethics</td>
                                <td>Importance in Logistics Industry</td>
                                <td>Explore why integrity and ethics are crucial in the logistics sector.</td>
                                <td>Case Study</td>
                                <td>LSC/N1907</td>
                                <td>Industry Examples</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 63 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Integrity & Ethics</td>
                                <td>Regulatory Requirements in Logistics</td>
                                <td>Understand legal and regulatory frameworks governing logistics operations.</td>
                                <td>Lecture</td>
                                <td>LSC/N1907</td>
                                <td>Regulatory Guidelines</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Integrity & Ethics</td>
                                <td>Legal Compliance Case Studies</td>
                                <td>Analyze real-world cases of compliance and non-compliance.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N1907</td>
                                <td>Case Study Materials</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 64 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Integrity & Ethics</td>
                                <td>Data Security Practices</td>
                                <td>Learn best practices for securing sensitive data.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1907</td>
                                <td>Security Tools</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Integrity & Ethics</td>
                                <td>Cyber Security Awareness</td>
                                <td>Raise awareness about cyber threats and prevention.</td>
                                <td>Presentation</td>
                                <td>LSC/N1907</td>
                                <td>Cyber Security PPT</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Integrity & Ethics</td>
                                <td>Practical Scenario Discussion</td>
                                <td>Discuss practical scenarios related to data security.</td>
                                <td>Workshop</td>
                                <td>LSC/N1907</td>
                                <td>Scenario Cards</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 65 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Integrity & Ethics</td>
                                <td>Corrupt Practices in Operations</td>
                                <td>Identify common corrupt practices in logistics operations.</td>
                                <td>Lecture</td>
                                <td>LSC/N1907</td>
                                <td>Examples List</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Integrity & Ethics</td>
                                <td>Real-Life Case Analysis</td>
                                <td>Analyze real-life cases of corruption.</td>
                                <td>Case Study</td>
                                <td>LSC/N1907</td>
                                <td>Case Files</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Integrity & Ethics</td>
                                <td>Consequences & Prevention</td>
                                <td>Understand consequences of corruption and prevention strategies.</td>
                                <td>Discussion</td>
                                <td>LSC/N1907</td>
                                <td>Prevention Guidelines</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 66 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Integrity & Ethics</td>
                                <td>Code of Conduct</td>
                                <td>Review the organization's code of conduct.</td>
                                <td>Lecture</td>
                                <td>LSC/N1907</td>
                                <td>Code Document</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Integrity & Ethics</td>
                                <td>Documentation of Violations</td>
                                <td>Learn how to document ethical violations.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1907</td>
                                <td>Forms</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Integrity & Ethics</td>
                                <td>Reporting Procedures</td>
                                <td>Understand procedures for reporting violations.</td>
                                <td>Flowchart Explanation</td>
                                <td>LSC/N1907</td>
                                <td>Process Map</td>
                                <td>2 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 67 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Integrity & Ethics</td>
                                <td>Escalation Matrix</td>
                                <td>Learn the escalation matrix for ethical issues.</td>
                                <td>Presentation</td>
                                <td>LSC/N1907</td>
                                <td>Matrix Chart</td>
                                <td>1.5 Hours</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Integrity & Ethics</td>
                                <td>Whistleblower Policy</td>
                                <td>Understand the whistleblower protection policy.</td>
                                <td>Lecture</td>
                                <td>LSC/N1907</td>
                                <td>Policy Document</td>
                                <td>1.5 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Integrity & Ethics</td>
                                <td>Ethical Dilemma Role Play</td>
                                <td>Participate in role plays involving ethical dilemmas.</td>
                                <td>Role Play</td>
                                <td>LSC/N1907</td>
                                <td>Scenarios</td>
                                <td>1.5 Hours</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Integrity & Ethics</td>
                                <td>Final Assessment & Feedback</td>
                                <td>Assess learning outcomes and provide feedback.</td>
                                <td>Test + Discussion</td>
                                <td>Assessment</td>
                                <td>Question Paper, Feedback Form</td>
                                <td>1.5 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 8 Placeholder -->
            <div id="module8" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Module 8</h1>

                        <a href="{{ asset('pdf/module8.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <p style="text-align:center;">Content for Module 8 goes here...</p>
            </div>

            <!-- Module 9 Placeholder -->
            <div id="module9" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Module 9</h1>

                        <a href="{{ asset('pdf/module9.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <p style="text-align:center;">Content for Module 9 goes here...</p>
            </div>

            <!-- Module 10 -->
            <div id="module10" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Module 10</h1>

                        <a href="{{ asset('pdf/module10.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <p style="text-align:center;">Content for Module 10 goes here...</p>
            </div>

            <!-- Module 11 -->
            <div id="module11" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Forecasting & Trend Analysis (Option)</h1>

                        <a href="{{ asset('pdf/module11.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
                    Duration: 66 Hours (Day 101 – Day 111 | 6 Hours per Day)
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Module Name</th>
                                <th>Session Name</th>
                                <th>Session Objectives</th>
                                <th>Methodology</th>
                                <th>NOS Reference</th>
                                <th>Training Tools/Aids</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="day-section">
                                <td colspan="8">Day 101 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Introduction to Forecasting & Trend Analysis - Theory</td>
                                <td>Understand the meaning of forecasting, its importance in logistics & courier
                                    industry, difference between forecasting and estimation, and its role in business
                                    decision-making.</td>
                                <td>Lecture + Discussion</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Whiteboard</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Introduction to Forecasting & Trend Analysis - Practical</td>
                                <td>Understand historical sales data, identify patterns manually, and perform simple
                                    trend spotting exercises.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Data Sheets, Worksheets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 102 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Types of Trend Analysis - Theory</td>
                                <td>Learn about upward trend, downward trend, seasonal trend, cyclical trend, and
                                    irregular trend.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Examples</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Types of Trend Analysis - Practical</td>
                                <td>Identify trends from sample data sheets and discuss case studies.</td>
                                <td>Practical Exercise + Discussion</td>
                                <td>LSC/N1911</td>
                                <td>Data Sheets, Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 103 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Data Collection for Trend Analysis - Theory</td>
                                <td>Understand requirements for sales data, operational data, customer data, financial
                                    data, and impact of external factors.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Data Examples</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Data Collection for Trend Analysis - Practical</td>
                                <td>Create structured data sheets in Excel and organize raw data.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 104 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Moving Average Method - Theory</td>
                                <td>Learn the concept of moving average and its importance in smoothing data.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Formulas</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Moving Average Method - Practical</td>
                                <td>Calculate 3-month moving average in Excel and interpret results.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software, Sample Data</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 105 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Weighted Moving Average - Theory</td>
                                <td>Understand assigning weights to recent data and comparison with simple moving
                                    average.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Examples</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Weighted Moving Average - Practical</td>
                                <td>Perform weighted forecast calculations and compare errors.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software, Worksheets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 106 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Linear Regression Forecasting - Theory</td>
                                <td>Learn the basic concept of regression and independent & dependent variables.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Diagrams</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Linear Regression Forecasting - Practical</td>
                                <td>Use Excel regression function, plot trend line graphs, and predict next month
                                    volume.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software, Data Sets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 107 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Time Series & Seasonal Forecasting - Theory</td>
                                <td>Understand time series components: trend, seasonality, cyclical, and random
                                    variation.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Charts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Time Series & Seasonal Forecasting - Practical</td>
                                <td>Calculate seasonal index and forecast with seasonal adjustment.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software, Sample Data</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 108 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Qualitative & Scenario-Based Forecasting</td>
                                <td>Learn expert opinion method, Delphi technique, and scenario forecasting (Best,
                                    Moderate, Worst).</td>
                                <td>Lecture + Group Activity</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Scenario Templates</td>
                                <td>6 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 109 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Forecast Accuracy & Error Measurement - Theory</td>
                                <td>Understand forecast error, MAD (Mean Absolute Deviation), MAPE, and tracking signal.
                                </td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Formulas</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Forecast Accuracy & Error Measurement - Practical</td>
                                <td>Calculate forecast error and compare different forecasting methods.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1911</td>
                                <td>Excel Software, Data Sets</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 110 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Presenting Forecast to Management - Theory</td>
                                <td>Learn presentation structure, executive summary writing, and risk analysis.</td>
                                <td>Lecture</td>
                                <td>LSC/N1911</td>
                                <td>PPT, Templates</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Presenting Forecast to Management - Practical</td>
                                <td>Prepare forecast presentation (Group Work) and create charts & dashboards.</td>
                                <td>Group Work + Practical</td>
                                <td>LSC/N1911</td>
                                <td>Presentation Software, Excel</td>
                                <td>3 Hours</td>
                            </tr>

                            <tr class="day-section">
                                <td colspan="8">Day 111 (Total: 6 Hours)</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Assessment & Final Project - Practical Project</td>
                                <td>Analyze 12 months courier data, identify trends, apply forecasting methods, and
                                    prepare management report.</td>
                                <td>Project Work</td>
                                <td>LSC/N1911</td>
                                <td>Data Sets, Excel, Report Templates</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Forecasting & Trend Analysis</td>
                                <td>Assessment & Final Project - Assessment</td>
                                <td>Conduct viva, written test, and Excel practical test.</td>
                                <td>Assessment</td>
                                <td>Assessment</td>
                                <td>Question Papers, Excel Tests</td>
                                <td>3 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- End content-area -->
    </div> <!-- End page-layout -->

    <footer>
        <span style="font-weight: 600;">Sane Overseas Private Limited</span><br>
        Sane Overseas Pvt Ltd
        Plot No-1634, Second floor
        Sector-82 JLPL
        Website: <a href="https://www.saneoverseas.in/" target="_blank">www.saneoverseas.in</a>
    </footer>
    </div>
    </div>

</body>

</html>