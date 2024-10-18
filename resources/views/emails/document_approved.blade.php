<!DOCTYPE html>
<html>
<head>
    <title>Document Approved</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        /* Container for the content */
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Headings */
        h1 {
            color: #333333;
            margin-bottom: 10px;
        }

        /* Paragraphs */
        p {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
            margin: 10px 0;
        }

        /* Footer section */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #dddddd;
            padding-top: 10px;
        }

        .footer-img {
            margin-top: 10px;
            border-radius: 20px;
        }

        /* Image within the footer */
        .footer-img img {
            width:90%;
            height: auto;
            display: block;
        }

        /* Strong text */
        strong {
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Document Approved</h1>
        <p>Hello,</p>
        <p>We are pleased to inform you that the document titled "<strong>{{ $documentTitle }}</strong>" has been fully approved on <strong>{{ $approvalDate }}</strong>.</p>
        <p>The document is now ready for collection.</p>
        <p>If you have any questions, feel free to contact us.</p>

        <div class="footer">
            <p>Thank you,</p>
            <p>College of Information and Computing Local Council</p>

            <div class="footer-img">
                <a href="https://drive.google.com/drive-viewer/AKGpihYM46G_4fTO6-3G2G-YQui0OCvLLtJVb4Emz7M5B_43ncr2lRHIElf95zTeifQwO8HMPUyWPdoBr0fVniu71sDCyEGvYMrFP6w=s1600-rw-v1?source=screenshot.guru"> <img src="https://drive.google.com/drive-viewer/AKGpihYM46G_4fTO6-3G2G-YQui0OCvLLtJVb4Emz7M5B_43ncr2lRHIElf95zTeifQwO8HMPUyWPdoBr0fVniu71sDCyEGvYMrFP6w=s1600-rw-v1" /> </a>
            </div>
        </div>
    </div>
</body>
</html>
