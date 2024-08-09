<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Holiday Plan: {{ $holidayPlan->title }}</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #f9f9f9;
            --text-color: #333;
            --border-color: #ddd;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            background-color: var(--background-color);
        }
        .container {
            margin: 40px auto;
            max-width: 800px;
            padding: 30px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        .details {
            margin-top: 30px;
        }
        .details label {
            font-weight: bold;
            color: var(--secondary-color);
            display: block;
            margin-bottom: 5px;
        }
        .details p {
            margin: 0 0 20px 0;
            padding: 10px;
            background-color: var(--background-color);
            border-radius: 6px;
        }
        .details ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .details ul li {
            margin: 10px 0;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
            text-align: center;
            color: #777;
            font-style: italic;
        }
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            .title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">{{ $holidayPlan->title }}</h1>
        <div class="details">
            <label>Description</label>
            <p>{{ $holidayPlan->description }}</p>
            
            <label>Date</label>
            <p>{{ $holidayPlan->date }}</p>
            
            <label>Location</label>
            <p>{{ $holidayPlan->location }}</p>
            
            @if ($holidayPlan->participants)
                <label>Participants</label>
                <ul>
                    @foreach ($holidayPlan->participants as $participant)
                        <li>{{ $participant }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="footer">
            Thank you for reviewing the holiday plan! We hope you have a fantastic time.
        </div>
    </div>
</body>
</html>