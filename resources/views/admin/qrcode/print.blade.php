<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Meta CSRF Token (Security Best Practice) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>QR Table {{ $nomor_meja }}</title>
    
    <style>
        /* * 1. Page Setup 
         * Custom sticker size (10cm x 12cm)
         */
        @page { 
            size: 10cm 12cm; 
            margin: 0; 
        } 
        
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            text-align: center; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background-color: #fff;
        }
        
        /* * 2. Component: Sticker Box 
         * Main container for the sticker content
         */
        .sticker-box {
            border: 8px solid #000;
            padding: 25px;
            display: inline-block;
            border-radius: 20px;
            width: 300px;
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }
        
        /* * 3. Typography & Elements 
         */
        .title { 
            font-size: 24px; 
            font-weight: 800; 
            text-transform: uppercase; 
            margin-bottom: 15px; 
            letter-spacing: 1px; 
        }
        
        .table-no { 
            font-size: 48px; 
            font-weight: 900; 
            margin: 15px 0; 
            color: #d32f2f; /* Red accent for visibility */
            border-top: 2px dashed #ccc; 
            border-bottom: 2px dashed #ccc; 
            padding: 10px 0; 
        }
        
        .scan-text { 
            font-size: 14px; 
            margin-top: 15px; 
            font-style: italic; 
            color: #555; 
            line-height: 1.4;
        }
        
        .brand { 
            margin-top: 20px; 
            font-size: 12px; 
            font-weight: bold; 
            color: #000; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        
        /* QR Code Sizing */
        .qr-area svg { 
            width: 100%; 
            height: auto; 
            max-width: 200px; /* Limit max width */
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="sticker-box">
        {{-- Call to Action --}}
        <div class="title">Scan Here</div>
        
        {{-- QR Code Container --}}
        <div class="qr-area">
            {!! $qrcode !!}
        </div>

        {{-- Table Number --}}
        <div class="table-no">TABLE {{ $nomor_meja }}</div>
        
        {{-- Instructions --}}
        <div class="scan-text">
            Open your camera & scan<br>to order menu.
        </div>
        
        {{-- Branding --}}
        <div class="brand">Tikako Caffe & Java Culinary</div>
    </div>

</body>
</html>