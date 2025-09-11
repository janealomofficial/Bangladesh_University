<?php
    require_once __DIR__ . "/includes/header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Bangladesh University</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .university-image{
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80%; 
            
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h1, h2 {
            color: #1a237e; /* Dark blue from the provided images */
        }
        h1 {
            text-align: center;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 10px;
        }
        p {
            margin-bottom: 20px;
            text-align: justify;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #1a237e;
            font-size: 1.5em;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .founder-info {
            display: flex;
            align-items: center;
        }
        .founder-info p {
            margin: 0;
        }
        .founder-info h3 {
            margin-top: 0;
            color: #1a237e;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>About Bangladesh University</h1>

    <div class="section">
        <img src="assets/images/BU-Campus.jpg" alt="Bangladesh University Campus" class="university-image">
        </div>

    <div class="section">
        <h2 class="section-title">Overview</h2>
        <p>Bangladesh University (BU) was established in 2001 under the Private University Act, 1992 by Mr. Quazi Azher Ali, as a non-profit, non-political private university pioneering in computer-based education. It has been accredited by the Government of the People’s Republic of Bangladesh. Its curricula as well as programs have been approved by the University Grants Commission (UGC) of Bangladesh. BU is approved by the Government of Bangladesh for awarding degrees in various disciplines.</p>
        <p>We are committed to excellence and innovation in pursuing applied knowledge through research and creative activities with the objectives of producing world-class professionals responsive to the needs of the Bangladeshi community and the world at large.</p>
    </div>

    <div class="section">
        <h2 class="section-title">Growth and Community</h2>
        <p>BU started its academic session with 17 students and 0.02 acres of space in 2001. Now BU can accommodate around 3400 students in its own aesthetic permanent campus. With 85 full-time and 20 part-time faculty members and 110 administrative members, officials and staffs, our community continues to grow to cater to the needs of both the students and the nation as well.</p>
    </div>

    <div class="section">
        <h2 class="section-title">Permanent Campus</h2>
        <p>The permanent campus is located at <strong>5/B, Beribandh Main Road, Adabar, Mohammadpur, Dhaka</strong>, which is well connected with other parts of Dhaka city. The permanent campus is having 1.7026 acres of land and it was inaugurated by Advocate Jahangir Kabir Nanak, MP and former Hon’ble State Minister for Local Govt., Rural Development and Co-operatives on October 18, 2011.</p>
    </div>

    <div class="section">
        <h2 class="section-title">Founder</h2>
        <div class="founder-info">
            <p>The President of the People’s Republic of Bangladesh is the Chancellor of Bangladesh University (BU). He appointed <strong>Quazi Azher Ali, M.Sc. (DU), MPA (Harvard)</strong>, the founder of BU as its Vice Chancellor. He held this position from its inception in October 2001 until the day of his death on December 15, 2009. He had high academic achievement with long and varied experiences in national and international administration, including Secretary to the Government of Bangladesh and Executive Director of Asian Development Bank (ADB). Mr. Ali agreed to accept taka one per month as salary considering the initial financial problems for a new non-commercial institution.</p>
        </div>
    </div>
</div>

</body>
</html>

<?php
    require_once __DIR__ . "/includes/footer.php";
?>