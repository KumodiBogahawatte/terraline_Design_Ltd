#!/bin/bash

echo "🚀 Setting up fonts for Architecture Firm Website..."
echo "=================================================="

# Step 1: Create directories
echo "📁 Creating font directories..."
mkdir -p assets/fonts/{playfair-display,inter,cormorant-garamond}

# Step 2: Download fonts (simplified - using Google Fonts helper URLs)
echo "📥 Downloading fonts..."

# Playfair Display
echo "  - Playfair Display..."
curl -L -o assets/fonts/playfair-display/PlayfairDisplay-Regular.ttf "https://fonts.google.com/download?family=Playfair%20Display" 2>/dev/null

# Inter
echo "  - Inter..."
curl -L -o assets/fonts/inter/Inter.ttf "https://fonts.google.com/download?family=Inter" 2>/dev/null

# Step 3: Check if Python is installed for conversion
echo "🔧 Checking for font conversion tools..."
if command -v python3 &>/dev/null; then
    echo "  Python found, installing fonttools..."
    pip3 install --user fonttools brotli
    
    # Convert Playfair
    cd assets/fonts/playfair-display
    for file in *.ttf; do
        if [ -f "$file" ]; then
            fonttools ttLib.woff2 compress "$file" -o "${file%.ttf}.woff2" 2>/dev/null
            echo "  Converted: $file"
        fi
    done
    cd ../../..
    
    # Convert Inter
    cd assets/fonts/inter
    for file in *.ttf; do
        if [ -f "$file" ]; then
            fonttools ttLib.woff2 compress "$file" -o "${file%.ttf}.woff2" 2>/dev/null
            echo "  Converted: $file"
        fi
    done
    cd ../../..
else
    echo "⚠️  Python not found. Please install Python or convert fonts manually."
    echo "   Visit: https://cloudconvert.com/ttf-to-woff2"
fi

# Step 4: Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 assets/fonts

# Step 5: Create font CSS
echo "📝 Creating fonts.css..."
cat > assets/css/fonts.css << 'EOF'
/* Auto-generated fonts.css */
@font-face {
    font-family: 'Playfair Display';
    src: url('../fonts/playfair-display/PlayfairDisplay-Regular.woff2') format('woff2'),
         url('../fonts/playfair-display/PlayfairDisplay-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Inter';
    src: url('../fonts/inter/Inter-Regular.woff2') format('woff2'),
         url('../fonts/inter/Inter-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}
EOF

echo "✅ Font setup complete!"
echo "📁 Fonts installed in: assets/fonts/"
echo "📄 CSS generated: assets/css/fonts.css"
