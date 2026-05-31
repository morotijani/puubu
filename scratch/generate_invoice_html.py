import base64
import os

def create_invoice_html():
    logo_path = r"c:\xampp\htdocs\kokuromotie\media\logo_transparent.png"
    output_html = r"C:\Users\tijan\.gemini\antigravity\brain\a30eef2a-ac1c-49a5-90e5-6e81a3183b2f\kokuromotie_pro_forma_invoice.html"
    
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
    <title>Kokuromotie Pro Forma Invoice</title>
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

        @media print {{
            body {{ background-color: transparent; }}
            .page {{ margin: 0; box-shadow: none; width: 100%; min-height: 100vh; }}
        }}

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

        .content {{
            flex: 1;
            padding: 50px;
            font-size: 14px;
            line-height: 1.6;
        }}

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
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 40px; font-weight: 600;">
                <span><strong>To:</strong> [Organization Name]<br>[Client Address]</span>
                <span style="text-align: right;"><strong>Invoice No:</strong> PRO-2026-001<br><strong>Valid Until:</strong> June 30, 2026</span>
            </div>

            <h1 style="text-align: center; color: var(--dark-accent); border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 30px;">PRO FORMA INVOICE</h1>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                    <tr style="background-color: var(--light-gray); border-bottom: 2px solid var(--dark-accent);">
                        <th style="padding: 10px; text-align: left;">Item</th>
                        <th style="padding: 10px; text-align: left;">Description</th>
                        <th style="padding: 10px; text-align: center;">Qty</th>
                        <th style="padding: 10px; text-align: right;">Unit Price</th>
                        <th style="padding: 10px; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 10px; vertical-align: top;"><strong>Platform License</strong></td>
                        <td style="padding: 12px 10px; color: #64748b; vertical-align: top;">Core access to the Kokuromotie voting system (Tier: Up to 1,000 Voters). Includes secure ballot hosting and live intelligence dashboard.</td>
                        <td style="padding: 12px 10px; text-align: center; vertical-align: top;">1</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$450.00</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$450.00</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 10px; vertical-align: top;"><strong>Voter Data Setup</strong></td>
                        <td style="padding: 12px 10px; color: #64748b; vertical-align: top;">Formatting, cleansing, and bulk-importing of 960 voter identities into the system's secured database.</td>
                        <td style="padding: 12px 10px; text-align: center; vertical-align: top;">1</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$100.00</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$100.00</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 10px; vertical-align: top;"><strong>Session Config</strong></td>
                        <td style="padding: 12px 10px; color: #64748b; vertical-align: top;">Creation of election matrices, configuring positions, candidates, and custom authentication protocols.</td>
                        <td style="padding: 12px 10px; text-align: center; vertical-align: top;">1</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$150.00</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$150.00</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 10px; vertical-align: top;"><strong>Auth Credits</strong></td>
                        <td style="padding: 12px 10px; color: #64748b; vertical-align: top;">Security tokens and communication credits (email notifications / SMS OTP integration) for up to 960 voters.</td>
                        <td style="padding: 12px 10px; text-align: center; vertical-align: top;">1</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$90.00</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$90.00</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 10px; vertical-align: top;"><strong>Live Support</strong></td>
                        <td style="padding: 12px 10px; color: #64748b; vertical-align: top;">Technical support during the election window and generation of final certified audit reports.</td>
                        <td style="padding: 12px 10px; text-align: center; vertical-align: top;">1</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$100.00</td>
                        <td style="padding: 12px 10px; text-align: right; vertical-align: top;">$100.00</td>
                    </tr>
                </tbody>
            </table>

            <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
                <table style="width: 300px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="padding: 8px; text-align: right;">$890.00</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; text-align: right;"><strong>Tax / VAT (0%):</strong></td>
                        <td style="padding: 8px; text-align: right;">$0.00</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--dark-accent); font-size: 18px;">
                        <td style="padding: 12px 8px; text-align: right; color: var(--dark-accent);"><strong>TOTAL DUE:</strong></td>
                        <td style="padding: 12px 8px; text-align: right; color: var(--dark-accent);"><strong>$890.00</strong></td>
                    </tr>
                </table>
            </div>

            <div style="background-color: var(--light-gray); padding: 20px; border-left: 4px solid var(--primary-accent); border-radius: 0 8px 8px 0;">
                <h4 style="margin-top: 0; margin-bottom: 10px; color: var(--dark-accent);">Terms and Conditions</h4>
                <ol style="margin-bottom: 0; padding-left: 20px; color: #64748b; font-size: 12px;">
                    <li style="margin-bottom: 5px;"><strong>Validity:</strong> This pro forma invoice is valid for 30 days from the date of issue.</li>
                    <li style="margin-bottom: 5px;"><strong>Payment Terms:</strong> A minimum deposit of 60% is required to commence configuration and voter data import. The remaining 40% balance is due 24 hours prior to the election start date.</li>
                    <li style="margin-bottom: 5px;"><strong>Capacity:</strong> This invoice covers up to 1,000 voters. Exceeding this limit will require an upgrade to the next platform tier.</li>
                    <li><strong>Payment Methods:</strong> Payments can be made via Bank Transfer or Mobile Money. Account details provided upon acceptance.</li>
                </ol>
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
        
    print(f"Invoice created at: {output_html}")

if __name__ == "__main__":
    create_invoice_html()
