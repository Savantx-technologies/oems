<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Courier Executive - Operations</title>
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
            gap: 15px;
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

        .list-disc {
            text-align: center;
            list-style: none;
        }

        .sub-heading {
            text-align: center;
        }

        .li {
            list-style: none;
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
                    <div class="header-title">
                        <h1>Courier Executive - Operations</h1>
                    </div>
                    <span class="header-logo">
                        <img src="{{ asset('storage/school-logos/NSDC-Preview.png') }}" alt="Right Logo"
                            onerror="this.onerror=null;this.src='{{ asset('NSDC-Preview.png') }}';">
                    </span>
                </div>

                <div class="info-box">
                    <p><span class="highlight">Qualification Pack:</span>LSC/Q1902</p>
                    <p><span class="highlight">Version:</span> 2.0</p>
                    <p><span class="highlight">Sector:</span> Logistics</p>
                    <p><span class="highlight">Sub-Sector:</span> Courier and Express Services</p>
                    <p><span class="highlight">Occupation:</span> Hub/ Branch Operations, Institutional Sales, Branch
                        Sales,
                        Customer Relationship Management</p>
                </div>

                 <div class="flex justify-center items-center w-full my-8">
                    <div class="training-outcome-box glass p-8 rounded-2xl shadow-lg w-full max-w-2xl">

                        <h2 class="text-3xl font-bold mb-4 text-center text-gray-800 tracking-tight">
                            Training Outcome
                        </h2>

                        <p class=" sub-heading text-lg mb-6 text-center text-gray-600">
                            At the end of this program, the student will be able to:
                        </p>

                        <ul class="list-disc space-y-4">
                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Contribute to business growth through data-driven decision-making
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Demonstrate professional ethics and workplace readiness
                                    </span>
                                </div>
                            </li>

                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Work as a Courier Executive – Operations independently
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Ensure statutory, safety, GST, and customs compliance
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Handle ERP-based reporting and data analysis
                                    </span>
                                </div>
                            </li>

                            <li>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mt-1"></i>
                                    <span class="text-justify">
                                        Manage customer service and sales activities
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <h1 style="text-align: left;">Introduction to Courier Executive - Operations</h1>
                    <a href="{{ asset('storage/module1.pdf') }}" target="_blank"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition shrink-0">
                        Download PDF
                    </a>
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
                        <a href="{{ asset('storage/module2.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                        <a href="{{ asset('storage/module3.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 18 </td>
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
                                <td colspan="8">Day 19 </td>
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
                                <td colspan="8">Day 20 </td>
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
                                <td colspan="8">Day 21 </td>
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
                                <td colspan="8">Day 22 </td>
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
                                <td colspan="8">Day 23 </td>
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
                                <td colspan="8">Day 24 </td>
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
                                <td colspan="8">Day 25 </td>
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
                                <td colspan="8">Day 26 </td>
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
                                <td colspan="8">Day 27 </td>
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
                                <td colspan="8">Day 28 </td>
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
                        <a href="{{ asset('storage/module4.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 29 </td>
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
                                <td colspan="8">Day 30 </td>
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
                                <td colspan="8">Day 31 </td>
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
                                <td colspan="8">Day 32 </td>
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
                                <td colspan="8">Day 33 </td>
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
                                <td colspan="8">Day 34 </td>
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
                                <td colspan="8">Day 35 </td>
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
                                <td colspan="8">Day 36 </td>
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
                                <td colspan="8">Day 37 </td>
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
                                <td colspan="8">Day 38 </td>
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
                                <td colspan="8">Day 39 </td>
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
                        <a href="{{ asset('storage/module5.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 40 </td>
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
                                <td colspan="8">Day 41 </td>
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
                                <td colspan="8">Day 42 </td>
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
                                <td colspan="8">Day 43 </td>
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
                                <td colspan="8">Day 44 </td>
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
                                <td colspan="8">Day 45 </td>
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
                                <td colspan="8">Day 46 </td>
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
                                <td colspan="8">Day 47 </td>
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
                                <td colspan="8">Day 48 </td>
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
                                <td colspan="8">Day 49 </td>
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
                                <td colspan="8">Day 50 </td>
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
                        <a href="{{ asset('storage/module6.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 51 </td>
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
                                <td colspan="8">Day 52 </td>
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
                                <td colspan="8">Day 53 </td>
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
                                <td colspan="8">Day 54 </td>
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
                                <td colspan="8">Day 55 </td>
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
                                <td colspan="8">Day 56 </td>
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
                                <td colspan="8">Day 57 </td>
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
                                <td colspan="8">Day 58 </td>
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
                                <td colspan="8">Day 59 </td>
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
                                <td colspan="8">Day 60 </td>
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
                                <td colspan="8">Day 61 </td>
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
                        <a href="{{ asset('storage/module7.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 62 </td>
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
                                <td colspan="8">Day 63 </td>
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
                                <td colspan="8">Day 64 </td>
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
                                <td colspan="8">Day 65 </td>
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
                                <td colspan="8">Day 66 </td>
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
                                <td colspan="8">Day 67 </td>
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
                        <h1>Health, Safety & Security Compliance</h1>

                        <a href="{{ asset('storage/module8.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                            <!-- Day 68 -->
                            <tr class="day-section">
                                <td colspan="8">Day 68 </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Health, Safety & Security</td>
                                <td>Introduction to Health & Safety</td>
                                <td>Understand the importance of health and safety at the workplace.</td>
                                <td>Lecture + Discussion</td>
                                <td>LSC/N0024</td>
                                <td>PPT, Safety Manual</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Health, Safety & Security</td>
                                <td>Warehouse Safety Overview</td>
                                <td>Identify common safety hazards in a warehouse environment.</td>
                                <td>Presentation</td>
                                <td>LSC/N0024</td>
                                <td>Images, Videos</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Health, Safety & Security</td>
                                <td>Importance of Compliance</td>
                                <td>Understand the legal and organizational importance of safety compliance.</td>
                                <td>Case Study</td>
                                <td>LSC/N0024</td>
                                <td>Case Study Handouts</td>
                                <td>2 Hours</td>
                            </tr>

                            <!-- Day 69 -->
                            <tr class="day-section">
                                <td colspan="8">Day 69 </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Health, Safety & Security</td>
                                <td>5S Concept</td>
                                <td>Learn the principles of 5S (Sort, Set in Order, Shine, Standardize, Sustain).</td>
                                <td>Lecture + Examples</td>
                                <td>LSC/N0024</td>
                                <td>5S Posters, PPT</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Health, Safety & Security</td>
                                <td>Practical 5S Implementation Activity</td>
                                <td>Apply 5S principles in a simulated workspace.</td>
                                <td>Group Activity</td>
                                <td>LSC/N0024</td>
                                <td>Designated Area, Labels</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 70 -->
                            <tr class="day-section">
                                <td colspan="8">Day 70 </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Health, Safety & Security</td>
                                <td>Daily Inspection Procedures</td>
                                <td>Learn how to conduct daily safety inspections of the work area.</td>
                                <td>Demonstration</td>
                                <td>LSC/N0024</td>
                                <td>Inspection Checklist</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Health, Safety & Security</td>
                                <td>Equipment Safety Check Practical</td>
                                <td>Practice performing safety checks on common equipment.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N0024</td>
                                <td>MHE, Scanners</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 71 -->
                            <tr class="day-section">
                                <td colspan="8">Day 71 </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Health, Safety & Security</td>
                                <td>Identifying Unsafe Conditions</td>
                                <td>Develop skills to spot potential hazards and unsafe conditions.</td>
                                <td>Image Analysis</td>
                                <td>LSC/N0024</td>
                                <td>Hazard Photos</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Health, Safety & Security</td>
                                <td>Risk Assessment Exercise</td>
                                <td>Conduct a basic risk assessment for a given scenario.</td>
                                <td>Workshop</td>
                                <td>LSC/N0024</td>
                                <td>Risk Matrix, Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 72 -->
                            <tr class="day-section">
                                <td colspan="8">Day 72 </td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Health, Safety & Security</td>
                                <td>Hazardous Goods Handling</td>
                                <td>Learn procedures for safely handling hazardous materials.</td>
                                <td>Lecture + Video</td>
                                <td>LSC/N0024</td>
                                <td>PPT, Videos</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Health, Safety & Security</td>
                                <td>MSDS & Label Reading Practical</td>
                                <td>Practice reading Material Safety Data Sheets (MSDS) and labels.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N0024</td>
                                <td>Sample MSDS, Labels</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 73 -->
                            <tr class="day-section">
                                <td colspan="8">Day 73 </td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Health, Safety & Security</td>
                                <td>Storage & Segregation Norms</td>
                                <td>Understand norms for storing and segregating different types of goods.</td>
                                <td>Lecture</td>
                                <td>LSC/N0024</td>
                                <td>Storage Guidelines</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Health, Safety & Security</td>
                                <td>Case Study Discussion</td>
                                <td>Discuss case studies related to improper storage and its consequences.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N0024</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 74 -->
                            <tr class="day-section">
                                <td colspan="8">Day 74 </td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Health, Safety & Security</td>
                                <td>Fire Safety Training</td>
                                <td>Learn about fire prevention and how to use fire extinguishers.</td>
                                <td>Demonstration</td>
                                <td>LSC/N0024</td>
                                <td>Fire Extinguisher</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Health, Safety & Security</td>
                                <td>Emergency Drill Practice</td>
                                <td>Participate in a simulated emergency evacuation drill.</td>
                                <td>Simulation</td>
                                <td>LSC/N0024</td>
                                <td>Evacuation Plan</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 75 -->
                            <tr class="day-section">
                                <td colspan="8">Day 75 </td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Health, Safety & Security</td>
                                <td>Accident Reporting Process</td>
                                <td>Understand the procedure for reporting workplace accidents.</td>
                                <td>Flowchart Explanation</td>
                                <td>LSC/N0024</td>
                                <td>Reporting Forms</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Health, Safety & Security</td>
                                <td>First Aid Basics</td>
                                <td>Learn basic first aid for common workplace injuries.</td>
                                <td>Demonstration</td>
                                <td>LSC/N0024</td>
                                <td>First Aid Kit</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 76 -->
                            <tr class="day-section">
                                <td colspan="8">Day 76 </td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Health, Safety & Security</td>
                                <td>Security Procedures</td>
                                <td>Understand general security procedures for the facility.</td>
                                <td>Lecture</td>
                                <td>LSC/N0024</td>
                                <td>Security Policy</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Health, Safety & Security</td>
                                <td>Access Control & CCTV Monitoring</td>
                                <td>Learn about access control systems and the role of CCTV.</td>
                                <td>Presentation</td>
                                <td>LSC/N0024</td>
                                <td>PPT, CCTV Footage</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 77 -->
                            <tr class="day-section">
                                <td colspan="8">Day 77 </td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Health, Safety & Security</td>
                                <td>Documentation of Violations</td>
                                <td>Learn how to document safety and security violations.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N0024</td>
                                <td>Violation Forms</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Health, Safety & Security</td>
                                <td>Compliance Audit Process</td>
                                <td>Understand the process of a safety and security compliance audit.</td>
                                <td>Lecture</td>
                                <td>LSC/N0024</td>
                                <td>Audit Checklist</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 78 -->
                            <tr class="day-section">
                                <td colspan="8">Day 78 </td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Health, Safety & Security</td>
                                <td>Escalation Matrix</td>
                                <td>Understand the escalation path for safety and security issues.</td>
                                <td>Presentation</td>
                                <td>LSC/N0024</td>
                                <td>Escalation Chart</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Health, Safety & Security</td>
                                <td>Integrated Case Study</td>
                                <td>Analyze a complex case involving health, safety, and security.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N0024</td>
                                <td>Case Study Document</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Health, Safety & Security</td>
                                <td>Final Assessment & Feedback</td>
                                <td>Assess module learning and provide feedback.</td>
                                <td>Test + Q&A</td>
                                <td>Assessment</td>
                                <td>Question Paper</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 9 Placeholder -->
            <div id="module9" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Verify GST Application</h1>
                        <a href="{{ asset('storage/module9.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                            <!-- Day 79 -->
                            <tr class="day-section">
                                <td colspan="8">Day 79 </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Verify GST Application</td>
                                <td>Introduction to GST</td>
                                <td>Understand the concept and purpose of Goods and Services Tax.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>PPT, Handouts</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Verify GST Application</td>
                                <td>GST Structure Overview</td>
                                <td>Learn about the structure and components of GST in India.</td>
                                <td>Presentation</td>
                                <td>LSC/N1905</td>
                                <td>Charts, Diagrams</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Verify GST Application</td>
                                <td>Importance in Courier Industry</td>
                                <td>Understand how GST impacts courier and logistics operations.</td>
                                <td>Discussion</td>
                                <td>LSC/N1905</td>
                                <td>Case Examples</td>
                                <td>2 Hours</td>
                            </tr>

                            <!-- Day 80 -->
                            <tr class="day-section">
                                <td colspan="8">Day 80 </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Verify GST Application</td>
                                <td>Location of Recipient vs Place of Supply</td>
                                <td>Distinguish between location of recipient and place of supply.</td>
                                <td>Lecture + Examples</td>
                                <td>LSC/N1905</td>
                                <td>PPT, Maps</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Verify GST Application</td>
                                <td>Case Study Practice</td>
                                <td>Apply place of supply rules to various scenarios.</td>
                                <td>Group Activity</td>
                                <td>LSC/N1905</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 81 -->
                            <tr class="day-section">
                                <td colspan="8">Day 81 </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Verify GST Application</td>
                                <td>Intra-State vs Inter-State Classification</td>
                                <td>Learn to classify transactions as intra-state or inter-state.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Flowcharts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Verify GST Application</td>
                                <td>Practical Exercises</td>
                                <td>Practice classifying shipments for GST purposes.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Worksheets</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 82 -->
                            <tr class="day-section">
                                <td colspan="8">Day 82 </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Verify GST Application</td>
                                <td>CGST, SGST, IGST Rules</td>
                                <td>Understand the application of CGST, SGST, and IGST.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Tax Tables</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Verify GST Application</td>
                                <td>Reverse Charge Mechanism</td>
                                <td>Understand the concept and application of Reverse Charge Mechanism (RCM).</td>
                                <td>Discussion</td>
                                <td>LSC/N1905</td>
                                <td>RCM Guide</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 83 -->
                            <tr class="day-section">
                                <td colspan="8">Day 83 </td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Verify GST Application</td>
                                <td>GST Documentation Requirements</td>
                                <td>Learn about the documents required for GST compliance.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Sample Docs</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Verify GST Application</td>
                                <td>Invoice Structure Study</td>
                                <td>Analyze the mandatory fields and structure of a GST invoice.</td>
                                <td>Document Review</td>
                                <td>LSC/N1905</td>
                                <td>Sample Invoices</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 84 -->
                            <tr class="day-section">
                                <td colspan="8">Day 84 </td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Verify GST Application</td>
                                <td>SAC & HSN Codes</td>
                                <td>Understand Service Accounting Codes (SAC) and HSN codes.</td>
                                <td>Lecture</td>
                                <td>LSC/N1905</td>
                                <td>Code Lists</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Verify GST Application</td>
                                <td>Practical Code Identification</td>
                                <td>Practice identifying correct SAC/HSN codes for services/goods.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Product/Service List</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 85 -->
                            <tr class="day-section">
                                <td colspan="8">Day 85 </td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Verify GST Application</td>
                                <td>GST Calculation Workshop</td>
                                <td>Learn to calculate GST amounts accurately.</td>
                                <td>Workshop</td>
                                <td>LSC/N1905</td>
                                <td>Calculator, Sheets</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Verify GST Application</td>
                                <td>Case-Based Computation</td>
                                <td>Solve complex GST calculation cases.</td>
                                <td>Practical Exercise</td>
                                <td>LSC/N1905</td>
                                <td>Case Scenarios</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 86 -->
                            <tr class="day-section">
                                <td colspan="8">Day 86 </td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Verify GST Application</td>
                                <td>GST Reversal & Credit Notes</td>
                                <td>Understand when and how to issue credit notes and reverse GST.</td>
                                <td>Lecture + Examples</td>
                                <td>LSC/N1905</td>
                                <td>Credit Note Samples</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Verify GST Application</td>
                                <td>Return Adjustment Process</td>
                                <td>Learn the process for adjusting returns in GST.</td>
                                <td>Flowchart</td>
                                <td>LSC/N1905</td>
                                <td>Process Map</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 87 -->
                            <tr class="day-section">
                                <td colspan="8">Day 87 </td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Verify GST Application</td>
                                <td>Invoice Inspection Process</td>
                                <td>Learn to inspect invoices for GST compliance errors.</td>
                                <td>Demonstration</td>
                                <td>LSC/N1905</td>
                                <td>Checklist</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Verify GST Application</td>
                                <td>Error Identification Activity</td>
                                <td>Identify errors in sample invoices.</td>
                                <td>Activity</td>
                                <td>LSC/N1905</td>
                                <td>Faulty Invoices</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 88 -->
                            <tr class="day-section">
                                <td colspan="8">Day 88 </td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Verify GST Application</td>
                                <td>Integrated GST Case Studies</td>
                                <td>Apply all GST concepts to comprehensive case studies.</td>
                                <td>Group Discussion</td>
                                <td>LSC/N1905</td>
                                <td>Case Study Booklet</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Verify GST Application</td>
                                <td>ERP Billing Entry Practice</td>
                                <td>Practice entering GST-compliant bills in the ERP system.</td>
                                <td>Lab Session</td>
                                <td>LSC/N1905</td>
                                <td>ERP System</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 89 -->
                            <tr class="day-section">
                                <td colspan="8">Day 89 </td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Verify GST Application</td>
                                <td>Final Assessment</td>
                                <td>Assess theoretical knowledge of GST application.</td>
                                <td>Written Test</td>
                                <td>Assessment</td>
                                <td>Question Paper</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Verify GST Application</td>
                                <td>Practical Test</td>
                                <td>Assess practical skills in calculation and verification.</td>
                                <td>Practical Test</td>
                                <td>Assessment</td>
                                <td>Test Sheets</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Verify GST Application</td>
                                <td>Module Review & Feedback</td>
                                <td>Review key learnings and collect feedback.</td>
                                <td>Discussion</td>
                                <td>N/A</td>
                                <td>Feedback Form</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 10 -->
            <div id="module10" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Employability Skills</h1>
                        <a href="{{ asset('storage/module10.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                            <!-- Day 90 -->
                            <tr class="day-section">
                                <td colspan="8">Day 90 </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Employability Skills</td>
                                <td>Introduction to Employability Skills</td>
                                <td>Understand the significance of employability skills in career growth.</td>
                                <td>Lecture + Discussion</td>
                                <td>Employability Skills</td>
                                <td>PPT, Handouts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Employability Skills</td>
                                <td>Core Skills Overview</td>
                                <td>Identify core skills required for the job market.</td>
                                <td>Activity</td>
                                <td>Employability Skills</td>
                                <td>Skill Cards</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 91 -->
                            <tr class="day-section">
                                <td colspan="8">Day 91 </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Employability Skills</td>
                                <td>Government & Private Job Portals</td>
                                <td>Learn to navigate and register on various job portals.</td>
                                <td>Demonstration</td>
                                <td>Employability Skills</td>
                                <td>Computer Lab</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Employability Skills</td>
                                <td>Resume Creation Practical</td>
                                <td>Create a professional resume.</td>
                                <td>Practical Exercise</td>
                                <td>Employability Skills</td>
                                <td>Resume Templates</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 92 -->
                            <tr class="day-section">
                                <td colspan="8">Day 92 </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Employability Skills</td>
                                <td>Constitutional Values & Ethics</td>
                                <td>Understand constitutional values and professional ethics.</td>
                                <td>Lecture</td>
                                <td>Employability Skills</td>
                                <td>Constitution Chart</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Employability Skills</td>
                                <td>21st Century Skills</td>
                                <td>Learn about critical thinking, creativity, and collaboration.</td>
                                <td>Group Activity</td>
                                <td>Employability Skills</td>
                                <td>Activity Kit</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 93 -->
                            <tr class="day-section">
                                <td colspan="8">Day 93 </td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Employability Skills</td>
                                <td>Communication Skills</td>
                                <td>Enhance verbal and non-verbal communication.</td>
                                <td>Role Play</td>
                                <td>Employability Skills</td>
                                <td>Scenarios</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Employability Skills</td>
                                <td>Active Listening Practice</td>
                                <td>Practice active listening techniques.</td>
                                <td>Exercise</td>
                                <td>Employability Skills</td>
                                <td>Audio Clips</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Employability Skills</td>
                                <td>Teamwork Activities</td>
                                <td>Participate in team-building exercises.</td>
                                <td>Group Game</td>
                                <td>Employability Skills</td>
                                <td>Props</td>
                                <td>2 Hours</td>
                            </tr>

                            <!-- Day 94 -->
                            <tr class="day-section">
                                <td colspan="8">Day 94 </td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Employability Skills</td>
                                <td>POSH Act Awareness</td>
                                <td>Understand the Prevention of Sexual Harassment (POSH) Act.</td>
                                <td>Lecture + Video</td>
                                <td>Employability Skills</td>
                                <td>POSH Guidelines</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Employability Skills</td>
                                <td>Workplace Behaviour</td>
                                <td>Learn appropriate workplace conduct and etiquette.</td>
                                <td>Discussion</td>
                                <td>Employability Skills</td>
                                <td>Case Studies</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 95 -->
                            <tr class="day-section">
                                <td colspan="8">Day 95 </td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Employability Skills</td>
                                <td>Salary Structure</td>
                                <td>Understand components of salary (CTC, In-hand, PF, ESI).</td>
                                <td>Lecture</td>
                                <td>Employability Skills</td>
                                <td>Salary Slip Sample</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Employability Skills</td>
                                <td>Budget Planning Exercise</td>
                                <td>Learn personal financial planning and budgeting.</td>
                                <td>Workshop</td>
                                <td>Employability Skills</td>
                                <td>Worksheets</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 96 -->
                            <tr class="day-section">
                                <td colspan="8">Day 96 </td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Employability Skills</td>
                                <td>Legal Rights & Digital Literacy</td>
                                <td>Awareness of labor laws and basic digital literacy.</td>
                                <td>Lecture</td>
                                <td>Employability Skills</td>
                                <td>Handouts</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Employability Skills</td>
                                <td>Cyber Safety</td>
                                <td>Understand online safety and data privacy.</td>
                                <td>Presentation</td>
                                <td>Employability Skills</td>
                                <td>Safety Tips</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 97 -->
                            <tr class="day-section">
                                <td colspan="8">Day 97 </td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Employability Skills</td>
                                <td>Entrepreneurship Basics</td>
                                <td>Introduction to entrepreneurship concepts.</td>
                                <td>Lecture</td>
                                <td>Employability Skills</td>
                                <td>Success Stories</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Employability Skills</td>
                                <td>Business Idea Workshop</td>
                                <td>Brainstorm and develop business ideas.</td>
                                <td>Workshop</td>
                                <td>Employability Skills</td>
                                <td>Chart Paper</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 98 -->
                            <tr class="day-section">
                                <td colspan="8">Day 98 </td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Employability Skills</td>
                                <td>4Ps of Marketing</td>
                                <td>Understand Product, Price, Place, Promotion.</td>
                                <td>Lecture</td>
                                <td>Employability Skills</td>
                                <td>Marketing Mix Chart</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Employability Skills</td>
                                <td>Customer Need Analysis</td>
                                <td>Learn to identify and analyze customer needs.</td>
                                <td>Case Study</td>
                                <td>Employability Skills</td>
                                <td>Customer Profiles</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 99 -->
                            <tr class="day-section">
                                <td colspan="8">Day 99 </td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Employability Skills</td>
                                <td>Interview Skills</td>
                                <td>Preparation for job interviews (Do's and Don'ts).</td>
                                <td>Lecture + Video</td>
                                <td>Employability Skills</td>
                                <td>Interview Guide</td>
                                <td>3 Hours</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Employability Skills</td>
                                <td>Mock Interview Practice</td>
                                <td>Simulated interview sessions with feedback.</td>
                                <td>Role Play</td>
                                <td>Employability Skills</td>
                                <td>Evaluation Sheet</td>
                                <td>3 Hours</td>
                            </tr>

                            <!-- Day 100 -->
                            <tr class="day-section">
                                <td colspan="8">Day 100 </td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Employability Skills</td>
                                <td>Apprenticeship Registration</td>
                                <td>Guidance on registering for apprenticeship portals (NAPS).</td>
                                <td>Demonstration</td>
                                <td>Employability Skills</td>
                                <td>Computer Lab</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Employability Skills</td>
                                <td>Final Assessment</td>
                                <td>Assess understanding of employability skills.</td>
                                <td>Test</td>
                                <td>Assessment</td>
                                <td>Question Paper</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Employability Skills</td>
                                <td>Course Review & Feedback</td>
                                <td>Review the entire course and collect feedback.</td>
                                <td>Discussion</td>
                                <td>N/A</td>
                                <td>Feedback Form</td>
                                <td>2 Hours</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Module 11 -->
            <div id="module11" class="module-section">
                <div class="header-bar">
                    <div class="header-title flex items-center justify-between w-full">
                        <h1>Forecasting & Trend Analysis (Option)</h1>
                        <a href="{{ asset('storage/module11.pdf') }}" target="_blank"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="subtitle">
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
                                <td colspan="8">Day 101 </td>
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
                                <td colspan="8">Day 102 </td>
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
                                <td colspan="8">Day 103 </td>
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
                                <td colspan="8">Day 104 </td>
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
                                <td colspan="8">Day 105 </td>
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
                                <td colspan="8">Day 106 </td>
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
                                <td colspan="8">Day 107 </td>
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
                                <td colspan="8">Day 108 </td>
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
                                <td colspan="8">Day 109 </td>
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
                                <td colspan="8">Day 110 </td>
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
                                <td colspan="8">Day 111 </td>
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
