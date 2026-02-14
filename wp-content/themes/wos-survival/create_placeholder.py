from PIL import Image, ImageDraw

def create_placeholder_image(path):
    # Create a 1920x1080 image with a dark blue color (Slate 900 approx)
    width, height = 1920, 1080
    color = (15, 23, 42) # #0f172a
    
    img = Image.new('RGB', (width, height), color)
    
    # Add some subtle noise or gradient if possible, but solid is fine for flat design
    # Let's add a simple "Frost & Fire" text text in center if we can, but 
    # PIL default font might be small. Let's just keep it solid for now to be safe.
    
    try:
        img.save(path, quality=80)
        print(f"Successfully created {path}")
    except Exception as e:
        print(f"Error creating image: {e}")

if __name__ == "__main__":
    create_placeholder_image(r"i:\04_develop\howasaba-lab\wp-content\themes\wos-survival\assets\images\hero-bg.jpg")
