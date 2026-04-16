<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - Rich Railing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            min-height: 100vh;
            color: #e0e0e0;
            line-height: 1.6;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background: rgba(218, 165, 32, 0.1);
            color: #DAA520;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
            border: 1px solid rgba(218, 165, 32, 0.3);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(218, 165, 32, 0.2);
            border-color: rgba(218, 165, 32, 0.5);
        }

        .btn-back svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            stroke: #DAA520;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .article-content {
            background: rgba(45, 45, 45, 0.5);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(218, 165, 32, 0.1);
        }

        /* Styling untuk konten WYSIWYG */
        .wysiwyg-content {
            color: #e0e0e0;
        }

        .wysiwyg-content h1,
        .wysiwyg-content h2,
        .wysiwyg-content h3,
        .wysiwyg-content h4,
        .wysiwyg-content h5,
        .wysiwyg-content h6 {
            color: #ffffff;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: 600;
            line-height: 1.3;
        }

        .wysiwyg-content h1 {
            font-size: 32px;
            border-bottom: 2px solid rgba(218, 165, 32, 0.3);
            padding-bottom: 10px;
            margin-top: 0;
        }

        .wysiwyg-content h2 {
            font-size: 28px;
            color: #DAA520;
        }

        .wysiwyg-content h3 {
            font-size: 24px;
        }

        .wysiwyg-content h4 {
            font-size: 20px;
        }

        .wysiwyg-content p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #d0d0d0;
        }

        .wysiwyg-content a {
            color: #DAA520;
            text-decoration: none;
            border-bottom: 1px solid rgba(218, 165, 32, 0.3);
            transition: all 0.3s ease;
        }

        .wysiwyg-content a:hover {
            color: #FFD700;
            border-bottom-color: #FFD700;
        }

        .wysiwyg-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .wysiwyg-content ul,
        .wysiwyg-content ol {
            margin-bottom: 20px;
            padding-left: 30px;
        }

        .wysiwyg-content li {
            margin-bottom: 10px;
            color: #d0d0d0;
        }

        .wysiwyg-content ul li {
            list-style-type: none;
            position: relative;
        }

        .wysiwyg-content ul li::before {
            content: '■';
            color: #DAA520;
            position: absolute;
            left: -20px;
            font-size: 12px;
        }

        .wysiwyg-content blockquote {
            border-left: 4px solid #DAA520;
            padding-left: 20px;
            margin: 20px 0;
            font-style: italic;
            color: #b0b0b0;
            background: rgba(218, 165, 32, 0.05);
            padding: 15px 20px;
            border-radius: 5px;
        }

        .wysiwyg-content code {
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #FFD700;
        }

        .wysiwyg-content pre {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            margin: 20px 0;
            border: 1px solid rgba(218, 165, 32, 0.2);
        }

        .wysiwyg-content pre code {
            background: none;
            padding: 0;
        }

        .wysiwyg-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .wysiwyg-content table th,
        .wysiwyg-content table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(218, 165, 32, 0.1);
        }

        .wysiwyg-content table th {
            background: rgba(218, 165, 32, 0.2);
            color: #ffffff;
            font-weight: 600;
        }

        .wysiwyg-content table tr:hover {
            background: rgba(218, 165, 32, 0.05);
        }

        .wysiwyg-content hr {
            border: none;
            border-top: 1px solid rgba(218, 165, 32, 0.3);
            margin: 30px 0;
        }

        .wysiwyg-content strong,
        .wysiwyg-content b {
            color: #ffffff;
            font-weight: 600;
        }

        .wysiwyg-content em,
        .wysiwyg-content i {
            color: #b0b0b0;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .article-content {
                padding: 25px 20px;
            }

            .wysiwyg-content h1 {
                font-size: 26px;
            }

            .wysiwyg-content h2 {
                font-size: 22px;
            }

            .wysiwyg-content h3 {
                font-size: 20px;
            }

            .wysiwyg-content p {
                font-size: 15px;
            }

            .container {
                padding: 30px 15px;
            }
        }

        .footer-gradient {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to top, rgba(218, 165, 32, 0.2) 0%, transparent 100%);
            pointer-events: none;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="article-content">
            <div class="wysiwyg-content">
                <?= $konten ?>
            </div>
        </div>
    </div>

    <div class="footer-gradient"></div>
</body>
</html>
