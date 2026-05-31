import base64
import os

def create_letterhead():
    logo_path = r"c:\xampp\htdocs\kokuromotie\media\logo_transparent.png"
    output_html = r"C:\Users\tijan\.gemini\antigravity\brain\a30eef2a-ac1c-49a5-90e5-6e81a3183b2f\kokuromotie_letterhead.html"
    
    # Read and encode the logo
    try:
        with open(logo_path, "rb") as image_file:
            encoded_string = base64.b64encode(image_file.read()).decode("utf-8")
    except Exception as e:
        print(f"Error reading logo: {e}")
        return

    html_content = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kokuromotie Letterhead Template</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {{
            --primary-accent: #a3e635;
            --dark-accent: #1d3d37;
            --text-color: #334155;
            --light-gray: #f8fafc;
        }}
        
        body {{
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e2e8f0;
            display: flex;
            justify-content: center;
            color: var(--text-color);
        }}

        .page {{
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 40px auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }}

        /* Print Specifics */
        @media print {{
            body {{
                background-color: transparent;
            }}
            .page {{
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 100vh;
            }}
        }}

        /* Header Design */
        .header {{
            padding: 40px 50px 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 4px solid var(--dark-accent);
            position: relative;
        }}

        .header::after {{
            content: '';
            position: absolute;
            bottom: -4px;
            right: 0;
            width: 30%;
            height: 4px;
            background-color: var(--primary-accent);
        }}

        .brand {{
            display: flex;
            align-items: center;
            gap: 15px;
        }}

        .brand-logo {{
            width: 65px;
            height: 65px;
            object-fit: contain;
        }}

        .brand-text {{
            font-size: 28px;
            font-weight: 800;
            color: var(--dark-accent);
            letter-spacing: -1px;
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
        }}

        .brand-text span {{
            color: var(--primary-accent);
        }}

        .company-slogan {{
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 5px;
        }}

        .doc-meta {{
            text-align: right;
            font-size: 13px;
            color: #64748b;
        }}

        /* Body Content */
        .content {{
            flex: 1;
            padding: 50px;
            font-size: 14px;
            line-height: 1.6;
        }}

        /* Placeholder text styles for template demonstration */
        .content h1 {{
            font-size: 20px;
            color: var(--dark-accent);
            margin-top: 0;
        }}
        
        .content p {{
            margin-bottom: 20px;
        }}

        .date-ref {{
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            font-weight: 600;
        }}

        .signature-block {{
            margin-top: 60px;
        }}

        .signature-line {{
            width: 200px;
            border-bottom: 1px solid var(--dark-accent);
            margin-bottom: 10px;
        }}

        /* Footer Design */
        .footer {{
            background-color: var(--light-gray);
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            position: relative;
        }}
        
        .footer::before {{
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: var(--primary-accent);
        }}

        .footer-col {{
            display: flex;
            flex-direction: column;
            gap: 5px;
        }}

        .footer-col strong {{
            color: var(--dark-accent);
            font-size: 12px;
            margin-bottom: 2px;
        }}

        .decor-circle {{
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(163, 230, 53, 0.03);
            top: 200px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
        }}

    </style>
</head>
<body>

    <div class="page">
        <div class="decor-circle"></div>
        
        <!-- HEADER -->
        <div class="header">
            <div class="brand">
                <img src="data:image/png;base64,{encoded_string}" alt="Kokuromotie Logo" class="brand-logo">
                <div>
                    <h1 class="brand-text">KOKURO<span>MOTIE</span></h1>
                    <div class="company-slogan">Professional Digital Electoral System</div>
                </div>
            </div>
            <div class="doc-meta">
                <div><strong>Ref:</strong> KOK-2026-001</div>
                <div><strong>Date:</strong> [Current Date]</div>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="content" style="position: relative; z-index: 1;">
            
            <div class="date-ref">
                <span>To: [Recipient Name/Organization]</span>
            </div>

            <h1>[Document Title / Subject Line]</h1>
            
            <p>Dear [Name],</p>
            
            <p>This is a printable letterhead template for Kokuromotie. You can use this file to generate official correspondence, policy documents, quotes, and audit certifications. The layout is optimized for standard A4 printing and PDF export.</p>
            
            <p>Because the logo is base64 encoded directly into the HTML, this file is completely portable. You can copy it, move it, or email it, and the branding will remain intact without needing an internet connection or external image files.</p>
            
            <p>To use this for your business:</p>
            <ul>
                <li>Open this file in your text editor to replace this placeholder content.</li>
                <li>Open the file in a browser (like Chrome or Edge).</li>
                <li>Press <strong>Ctrl+P</strong> (or Cmd+P) to open the print dialog.</li>
                <li>Select "Save as PDF", ensure "Background graphics" is checked, and remove margins/headers for a perfect edge-to-edge finish.</li>
            </ul>

            <p>Sincerely,</p>
            
            <div class="signature-block">
                <div class="signature-line"></div>
                <strong>[Your Name]</strong><br>
                Electoral Systems Director<br>
                Kokuromotie
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-col">
                <strong>Address</strong>
                <span>[Your Company Building]</span>
                <span>[Your Street Name, City, ZIP]</span>
            </div>
            <div class="footer-col">
                <strong>Contact</strong>
                <span>+1 (555) 123-4567</span>
                <span>info@kokuromotie.com</span>
            </div>
            <div class="footer-col" style="text-align: right;">
                <strong>Online</strong>
                <span>www.kokuromotie.com</span>
                <span>@Kokuromotie</span>
            </div>
        </div>
    </div>

</body>
</html>"""
    
    with open(output_html, "w", encoding="utf-8") as f:
        f.write(html_content)
        
    print(f"Letterhead created at: {output_html}")

if __name__ == "__main__":
    create_letterhead()
