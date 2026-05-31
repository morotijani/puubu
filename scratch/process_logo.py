import sys
from PIL import Image

def process_logo(input_path, output_png_path, output_ico_path, output_svg_path):
    try:
        # Open the image
        img = Image.open(input_path).convert("RGBA")
        
        # Get data
        datas = img.getdata()
        
        # Replace white background with transparent
        newData = []
        for item in datas:
            # If the pixel is white or very close to white, make it transparent
            if item[0] > 240 and item[1] > 240 and item[2] > 240:
                newData.append((255, 255, 255, 0))
            else:
                newData.append(item)
                
        img.putdata(newData)
        
        # Save transparent PNG
        img.save(output_png_path, "PNG")
        
        # Save ICO (resized for standard favicon sizes)
        icon_sizes = [(16, 16), (32, 32), (48, 48), (64, 64)]
        img.save(output_ico_path, format="ICO", sizes=icon_sizes)
        
        # Generate SVG embedding the base64 PNG
        import base64
        with open(output_png_path, "rb") as image_file:
            encoded_string = base64.b64encode(image_file.read()).decode("utf-8")
            
        svg_content = f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {img.width} {img.height}">
  <image href="data:image/png;base64,{encoded_string}" width="{img.width}" height="{img.height}" />
</svg>"""
        with open(output_svg_path, "w") as f:
            f.write(svg_content)
            
        print("Success! Logo processed.")
    except Exception as e:
        print(f"Error: {str(e)}")

if __name__ == "__main__":
    input_file = r"C:\Users\tijan\.gemini\antigravity\brain\a30eef2a-ac1c-49a5-90e5-6e81a3183b2f\kokuromotie_logo_1780154885825.png"
    out_png = r"c:\xampp\htdocs\kokuromotie\media\logo_transparent.png"
    out_ico = r"c:\xampp\htdocs\kokuromotie\media\favicon.ico"
    out_svg = r"c:\xampp\htdocs\kokuromotie\media\logo.svg"
    
    process_logo(input_file, out_png, out_ico, out_svg)
