<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
        }
        
        .cards-row {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 20px 25px;
            padding-left: 70px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #2EB28A;
            min-width: 250px;
            position: relative;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(46, 178, 138, 0.2);
        }

        .card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .card-icon {
            font-size: 1.8rem;
            color: #2EB28A;
            position: absolute;
            left: 25px;
        }

        .card-title {
            font-size: 1rem;
            color: #000000;
            font-weight: 600;
        }

        .card-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cards-row">
            <?php
            // Sample data - in a real application, this would come from a database
            $cards = [
                [
                    'title' => 'Total Employees',
                    'icon' => 'fas fa-users',
                    'value' => 124
                ],
                [
                    'title' => 'Clocked In',
                    'icon' => 'fas fa-user-check',
                    'value' => 89
                ],
                [
                    'title' => 'Clocked Out',
                    'icon' => 'fas fa-user-clock',
                    'value' => 22
                ],
                [
                    'title' => 'Absent Employees',
                    'icon' => 'fas fa-user-times',
                    'value' => 13
                ]
            ];
            
            // Generate cards
            foreach ($cards as $card) {
                echo '
                <div class="card">
                    <div class="card-content">
                        <i class="' . $card['icon'] . ' card-icon"></i>
                        <div>
                            <div class="card-title">' . $card['title'] . '</div>
                            <div class="card-value">' . $card['value'] . '</div>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</body>
</html>