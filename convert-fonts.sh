#!/bin/bash

echo "🔄 Converting TTF to WOFF2..."
echo "=============================="

# Function to convert fonts
convert_fonts() {
    local font_dir=$1
    local font_name=$2
    
    echo "Converting $font_name..."
    
    if command -v fonttools &> /dev/null; then
        # Using fonttools
        cd "$font_dir"
        for file in *.ttf; do
            if [ -f "$file" ]; then
                fonttools ttLib.woff2 compress "$file"
                echo "  ✅ Converted: $file -> ${file%.ttf}.woff2"
            fi
        done
        cd - > /dev/null
    elif command -v ttf2woff2 &> /dev/null; then
        # Using ttf2woff2
        cd "$font_dir"
        for file in *.ttf; do
            if [ -f "$file" ]; then
                cat "$file" | ttf2woff2 > "${file%.ttf}.woff2"
                echo "  ✅ Converted: $file -> ${file%.ttf}.woff2"
            fi
        done
        cd - > /dev/null
    else
        echo "  ⚠️  No converter found. Please install one:"
        echo "     Option 1: pip install fonttools brotli"
        echo "     Option 2: npm install -g ttf2woff2"
        return 1
    fi
}

# Convert Playfair Display
convert_fonts "/c/xampp/htdocs/architecture/assets/fonts/playfair-display" "Playfair Display"

# Convert Inter  
convert_fonts "/c/xampp/htdocs/architecture/assets/fonts/inter" "Inter"

# Convert Cormorant Garamond (if you add files later)
if [ -d "/c/xampp/htdocs/architecture/assets/fonts/cormorant-garamond" ]; then
    if [ "$(ls -A /c/xampp/htdocs/architecture/assets/fonts/cormorant-garamond/*.ttf 2>/dev/null)" ]; then
        convert_fonts "/c/xampp/htdocs/architecture/assets/fonts/cormorant-garamond" "Cormorant Garamond"
    fi
fi

echo ""
echo "✅ Font conversion complete!"
echo "📁 Check your font directories for .woff2 files"